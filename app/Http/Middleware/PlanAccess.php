<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlanAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$allowedPlans
     */
    public function handle(Request $request, Closure $next, string ...$allowedPlans): Response
    {
        // 1. Mengambil data user berdasarkan guard masing-masing
        $restaurant = Auth::guard('restaurant')->user();
        $user = Auth::guard('web')->user();

        // 2. Simpan data ke dalam Session Laravel
        session([
            'auth_user' => $user,
            'restaurant_plan' => $restaurant ? $restaurant->plan : null,
        ]);

        // 3. Jika parameter allowedPlans kosong, langsung lanjut
        if (empty($allowedPlans)) {
            return $next($request);
        }

        // 4. Cek apakah plan restaurant ada di dalam list allowedPlans
        if (!$restaurant || !in_array($restaurant->plan, $allowedPlans)) {
            // Log info user jika gagal lolos pengecekan
            logger()->info('try', ['user' => $user]);

            return redirect('/not-found');
        }

        return $next($request);
    }
}
