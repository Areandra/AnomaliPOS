<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = UserService::allUser(Auth::guard('web')->user()->id, Auth::guard('restaurant')->user()->id);

        return view('users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->only([
            'name',
            'email',
            'role',
            'avatarUrl',
            'status',
            'password',
        ]);

        UserService::createOrUpdateUser([...$data, 'restaurant_id' => Auth::guard('restaurant')->user()->id]);

        return redirect('/users');
    }

    public function show(string $id): View
    {
        try {
            $user = UserService::getUser($id);

            return view('users.create', compact('user'));
        } catch (\Throwable $e) {
            if ($id === 'create') {
                return view('users.create');
            }

            return view('errors.not-found');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->only([
                'name',
                'email',
                'role',
                'avatarUrl',
                'status',
            ]);

            $user = UserService::getUser($id);

            UserService::createOrUpdateUser($data, $user);

            return redirect('/users');
        } catch (\Throwable $e) {
            return view('errors.not-found');
        }
    }

    public function updatePassword(Request $request, string $id)
    {
        try {
            $data = $request->only([
                'password',
            ]);

            $user = UserService::getUser($id);

            UserService::createOrUpdateUser($data, $user);

            return redirect('/users');
        } catch (\Throwable $e) {
            return view('errors.not-found');
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = UserService::getUser($id);

            $user->delete($user->id);

            return redirect('/users');
        } catch (\Throwable $e) {
            return view('errors.not-found');
        }
    }

    public function toggleStatus(Request $request)
    {
        try {
            $user = UserService::getUser($request->id, ['id', 'status']);

            $user->status = $user->status == 'active' ? 'disabled' : 'active';
            $user->save();

            return redirect('/users');
        } catch (\Throwable $e) {
            return view('errors.not-found');
        }
    }

    public function me(): View
    {
        $user = Auth::guard('web')->user();

        $shifts = Shift::query()
            ->where('user_id', $user->id)
            ->get();

        return view('me', [
            'user' => $user,
            'shifts' => $shifts,
        ]);
    }
}
