<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\TrustedDivice;
use App\Models\User;
use App\Mail\SendResetPasswordMail;
use App\Mail\SendDeviceVerificationMail;
use App\Mail\SendNewRestaurantRegistrationRequest;
use App\Mail\SendResetPinMail;
use App\Services\RestaurantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;
use \Illuminate\Contracts\View\View;

class AuthController extends Controller
{

    public function register(Request $request): JsonResponse
    {
        $validate = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'restaurant_name' => 'required|string',
            'restaurant_pin' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->role == 'admin' && $user->status == 'disabled') {
                return response()->json(['message' => 'Akun disabled', 'code' => 'disabled'], 401);
            }

            return response()->json(['message' => 'Credentials tidak valid', 'code' => 'invalid_credential'], 400);
        }

        $newRestaurantData = [
            'name' => $validate['restaurant_name'],
            'pin' => $validate['restaurant_pin'],
            'status' => 'disabled'
        ];

        $restaurant = Restaurant::create($newRestaurantData);

        $newUserData = [
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => $validate['password'],
            'restaurant_id' => $restaurant->id,
            'status' => 'disabled',
            'role' => 'admin',
        ];

        $newUser = User::create($newUserData);

        $signedUrl = URL::temporarySignedRoute(
            'account-activation',
            now()->addMinutes(5),
            ['userId' => $newUser->id]
        );

        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SendNewRestaurantRegistrationRequest($newUser, $signedUrl));

        return response()->json(['message' => 'Register Berhasil Silahkan Tunggu Akun Anda Di Aktifkan', 'code' => 'success'], 200);
    }

    public function activationProgres(Request $request): View{
        if (!$request->hasValidSignature()) {
            return view('activation_verification', [
                'status' => 'error',
                'message' => 'Tautan verifikasi telah kadaluarsa atau tidak valid.',
            ]);
        }

        $userId = $request->route('userId');
        $deviceName = $request->query('deviceName');
        $fingerprint = $request->query('fingerprint');
        $restaurantId = $request->query('restaurantId');
        $os = $request->query('os');
        $browser = $request->query('browser');

        $alreadyTrusted = TrustedDivice::where('user_id', $userId)
            ->where('device_fingerprint', $fingerprint)
            ->exists();

        if ($alreadyTrusted) {
            return view('device_verification', [
                'status' => 'error',
                'message' => 'Tautan sudah pernah digunakan. Perangkat ini sudah berstatus Terpercaya.',
            ]);
        }

        try {
            $user = User::where('id', $userId)
                ->where('restaurant_id', $restaurantId)
                ->firstOrFail();

            TrustedDivice::create([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId,
                'device_name' => $deviceName,
                'device_fingerprint' => $fingerprint,
            ]);

            // Mail::to($user->email)->send(new SendDeviceVerificationMail($user, 'success', [
            //     'deviceName' => $deviceName,
            //     'os' => $os,
            //     'browser' => $browser,
            // ]));

            return view('device_verification', [
                'status' => 'success',
                'message' => 'Perangkat Anda telah resmi terdaftar sebagai perangkat terpercaya.',
                'deviceName' => $deviceName,
            ]);
        } catch (\Exception $e) {
            return view('device_verification', [
                'status' => 'error',
                'message' => 'Gagal memverifikasi perangkat karena gangguan sistem.',
            ]);
        }
    }


    /**
     * Handle Login user / karyawan
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Cek User & Password (Menggantikan User.verifyCredentials)
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credentials tidak valid', 'code' => 'invalid_credential'], 400);
        }

        // 2. Cek apakah status disabled
        if ($user->status === 'disabled') {
            return response()->json(['message' => 'Akun disabled', 'code' => 'disabled'], 401);
        }

        // 3. Ambil device fingerprint dari cookie
        $deviceFingerprint = $request->cookie('device_fingerprint');

        if (!$deviceFingerprint) {
            return response()->json(['message' => 'Credentials tidak valid', 'code' => 'invalid_fp'], 400);
        }

        // 4. Periksa apakah perangkat dikenali (Gunakan bypass global scope bila perlu)
        $isTrusted = TrustedDivice::where('user_id', $user->id)
            ->where('device_fingerprint', $deviceFingerprint)
            ->exists();

        if (!$isTrusted) {
            return response()->json(['message' => 'Device Tidak Dikenali', 'code' => 'not_trusted'], 404);
        }

        // 5. Eksekusi Login menggunakan Default Web Guard
        Auth::guard('web')->login($user);

        // 6. Set Session Penanda Trusted Device
        $request->session()->put('device_trusted', true);

        return response()->json(['message' => 'Login Berhasil', 'code' => 'success'], 200);
    }

    /**
     * Memverifikasi cookie fingerprint baru dari sisi device klien
     */
    public function verifyNewDevice(Request $request): JsonResponse
    {
        $deviceFingerprint = $request->input('deviceFingerprint');

        if (!$deviceFingerprint) {
            return response()->json(['message' => 'Credentials tidak valid', 'code' => 'invalid_fp'], 400);
        }

        // Menggunakan hash hmac sha256 dengan APP_KEY bawaan Laravel
        $hashDeviceFingerprint = hash_hmac('sha256', $deviceFingerprint, config('app.key'));

        $isTrusted = TrustedDivice::where('user_id', Auth::guard('web')->id())
            ->where('device_fingerprint', $hashDeviceFingerprint)
            ->exists();

        if (!$isTrusted) {
            return response()->json(['message' => 'Device Tidak Dikenali', 'code' => 'not_trusted'], 404);
        }

        // Tempel Cookie (Max age 30 hari = 60 * 24 * 30 menit di Laravel)
        cookie()->queue('device_fingerprint', $hashDeviceFingerprint, 60 * 24 * 30, '/', null, true, true, false, 'Lax');

        $request->session()->put('device_trusted', true);

        return response()->json(['message' => 'Device Terverifikasi']);
    }

    /**
     * Permintaan ganti password via Email (Membuat Signed URL)
     */
    public function requestToChangePassword(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!$user) return response()->json(['message' => 'Credentials tidak valid', 'code' => 'invalid_credential'], 400);

        // Membuat Temporary Signed URL yang kedaluwarsa dalam 5 menit
        $signedUrl = URL::temporarySignedRoute(
            'change-password',
            now()->addMinutes(5),
            ['userId' => $user->id]
        );

        Mail::to($user->email)->send(new SendResetPasswordMail($user, $signedUrl));

        return response()->json(['message' => 'Email pemulihan kata sandi telah dikirim.']);
    }

    /**
     * Menampilkan Form Ganti Password (Render via Inertia)
     */
    public function showChangePassword(Request $request): View|RedirectResponse
    {
        // 1. Validasi Signed URL bawaan Laravel
        if (!$request->hasValidSignature()) {
            return redirect()->to('/login')->with('error', 'Tautan kedaluwarsa atau tidak valid.');
        }

        $userId = $request->route('userId');
        $user = User::findOrFail($userId);

        // 2. Simpan izin restriksi di session
        $request->session()->put('password_reset_allowed_id', $user->id);

        return view('auth/reset_password_form', [
            'userName' => $user->name,
            'userId' => $user->id,
        ]);
    }

    /**
     * Update Password Baru Eksekusi
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $allowedId = $request->session()->get('password_reset_allowed_id');
        $userId = $request->input('userId');

        if (!$allowedId || $allowedId !== (int) $userId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid atau telah berakhir.');
        }

        // 2. Validasi Input (Sama seperti VineJS)
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 3. Ambil user dan update password otomatis ter-hash (Laravel 11 otomatis meng-hash string kosong pada model User)
        $user = User::findOrFail($allowedId);
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Flush security session (Single Use)
        $request->session()->forget('password_reset_allowed_id');

        return redirect()->to('/login')->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Request otorisasi device asing ke Admin Restoran
     */
    public function requestToTrust(Request $request): JsonResponse
    {
        $request->validate([
            'deviceName' => 'required|string',
            'deviceFingerprint' => 'required|string',
            'os' => 'required|string',
            'browser' => 'required|string',
        ]);

        $user = Auth::guard('web')->user();

        // Cari admin restoran terkait
        $admin = User::where('role', 'admin')
            ->where('restaurant_id', $user->restaurant_id)
            ->firstOrFail();

        $hashDeviceFingerprint = hash_hmac('sha256', $request->input('deviceFingerprint'), config('app.key'));

        // Buat tautan persetujuan bertanda tangan (15 menit)
        $signedUrl = URL::temporarySignedRoute(
            'verify_device',
            now()->addMinutes(15),
            [
                'userId' => $user->id,
                'deviceName' => $request->input('deviceName'),
                'fingerprint' => $hashDeviceFingerprint,
                'restaurantId' => $user->restaurant_id,
                'os' => $request->input('os'),
                'browser' => $request->input('browser'),
            ]
        );

        cookie()->queue('device_fingerprint', $hashDeviceFingerprint, 60 * 24 * 30, '/', null, false, true, false, 'Lax');

        Mail::to($admin->email)->send(new SendDeviceVerificationMail($admin, 'attempt', [
            'deviceName' => $request->input('deviceName'),
            'os' => $request->input('os'),
            'browser' => $request->input('browser'),
        ], $signedUrl));

        return response()->json(['message' => 'Permintaan verifikasi perangkat telah dikirim ke Admin.']);
    }

    /**
     * Eksekusi verifikasi klik tautan oleh admin
     */
    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return view('device_verification', [
                'status' => 'error',
                'message' => 'Tautan verifikasi telah kadaluarsa atau tidak valid.',
            ]);
        }

        $userId = $request->route('userId');
        $deviceName = $request->query('deviceName');
        $fingerprint = $request->query('fingerprint');
        $restaurantId = $request->query('restaurantId');
        $os = $request->query('os');
        $browser = $request->query('browser');

        $alreadyTrusted = TrustedDivice::where('user_id', $userId)
            ->where('device_fingerprint', $fingerprint)
            ->exists();

        if ($alreadyTrusted) {
            return view('device_verification', [
                'status' => 'error',
                'message' => 'Tautan sudah pernah digunakan. Perangkat ini sudah berstatus Terpercaya.',
            ]);
        }

        try {
            $user = User::where('id', $userId)
                ->where('restaurant_id', $restaurantId)
                ->firstOrFail();

            TrustedDivice::create([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId,
                'device_name' => $deviceName,
                'device_fingerprint' => $fingerprint,
            ]);

            // Mail::to($user->email)->send(new SendDeviceVerificationMail($user, 'success', [
            //     'deviceName' => $deviceName,
            //     'os' => $os,
            //     'browser' => $browser,
            // ]));

            return view('device_verification', [
                'status' => 'success',
                'message' => 'Perangkat Anda telah resmi terdaftar sebagai perangkat terpercaya.',
                'deviceName' => $deviceName,
            ]);
        } catch (\Exception $e) {
            return view('device_verification', [
                'status' => 'error',
                'message' => 'Gagal memverifikasi perangkat karena gangguan sistem.',
            ]);
        }
    }

    /**
     * Request untuk mengubah PIN Restoran
     */
    public function requestToChangePin(Request $request): JsonResponse
    {
        $restaurant = Auth::guard('restaurant')->user();

        $admin = User::where('role', 'admin')
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        $signedUrl = URL::temporarySignedRoute(
            'change-restaurant-pin',
            now()->addMinutes(5),
            ['restaurantId' => $restaurant->id]
        );

        Mail::to($admin->email)->send(new SendResetPinMail($restaurant, $admin->email, $signedUrl));

        return response()->json(['message' => 'Email perubahan PIN berhasil dikirim.']);
    }

    /**
     * Menampilkan form ganti PIN restoran
     */
    public function showChangePin(Request $request): View|RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            return redirect()->to('/login')->with('error', 'Tautan kedaluwarsa atau tidak valid.');
        }

        $restaurantId = $request->route('restaurantId');
        $restaurant = Restaurant::findOrFail($restaurantId);

        $request->session()->put('pin_reset_allowed_id', $restaurant->id);

        return view('auth.reset_pin_form', [
            'restaurantName' => $restaurant->name,
            'restaurantId' => $restaurant->id,
        ]);
    }

    /**
     * Eksekusi update PIN Restoran baru
     */
    public function updatePin(Request $request): RedirectResponse
    {
        $allowedId = $request->session()->get('pin_reset_allowed_id');
        $restaurantId = $request->input('restaurantId');

        if (!$allowedId || $allowedId !== (int) $restaurantId) {
            return redirect()->to('/login')->with('error', 'Sesi tidak valid atau telah berakhir.');
        }

        $request->validate([
            'pin' => 'required|string|digits:6|confirmed', // Validasi angka 6 digit mirip regex bawaan kamu
        ]);

        $restaurant = Restaurant::findOrFail($allowedId);
        $restaurant->pin = Hash::make($request->pin);

        // Padanan 'cuid()' milik adonis paling aman di laravel adalah menggunakan Str::orderedUuid() atau Str::random()
        $restaurant->restaurant_uid = (string) Str::orderedUuid();
        $restaurant->save();

        $request->session()->forget('pin_reset_allowed_id');

        return redirect()->to('/login')->with('success', 'PIN Restoran berhasil diperbarui.');
    }

    /**
     * Verifikasi masuk gerbang operasional POS menggunakan PIN Restoran
     */
    public function restaurantPin(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();
        $pin = $request->input('pin');

        if (!$user) {
            return response()->json(['message' => 'User belum login', 'code' => 'unauthorized'], 401);
        }

        if (!$pin) {
            return response()->json(['message' => 'PIN wajib diisi', 'code' => 'pin_required'], 400);
        }

        $restaurant = Restaurant::where('id', $user->restaurant_id)
            ->select('id', 'restaurant_uid', 'pin')
            ->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant tidak ditemukan', 'code' => 'restaurant_not_found'], 404);
        }

        // Cek PIN Restoran
        if (!Hash::check($pin, $restaurant->pin)) {
            return response()->json(['message' => 'PIN restaurant tidak valid', 'code' => 'invalid_pin'], 400);
        }

        // Login ke guard restaurant
        Auth::guard('restaurant')->login($restaurant);

        $request->session()->put('auth_uid', $restaurant->restaurant_uid);

        // Arahkan ke rute berdasarkan role user saat ini
        $redirectUrl = match ($user->role) {
            'admin' => '/',
            'cashier' => '/cashier',
            'kitchen' => '/kitchen',
            default => null,
        };

        if ($redirectUrl === null) {
            $user->delete(); // Sesuai perilaku logic adonis milikmu jika role bermasalah
            return response()->json(['message' => 'Role tidak valid', 'code' => 'invalid_role'], 401);
        }

        return response()->json([
            'message' => 'PIN valid',
            'code' => 'success',
            'redirect' => $redirectUrl
        ], 200);
    }
}
