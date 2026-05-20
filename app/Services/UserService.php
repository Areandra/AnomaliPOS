<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Ambil semua user selain diri sendiri
     */
    public static function allUser(int $meId, int $rId): Collection
    {
        return User::query()
            ->where('id', '!=', $meId)
            ->where('restaurant_id', '=', $rId)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Buat atau update user
     */
    public static function createOrUpdateUser(array $data, ?User $user = null): array
    {
        $allowedRoles = [
            'cashier',
            'waiter',
            'kitchen',
            'manager',
        ];

        if (isset($data['role']) && !in_array($data['role'], $allowedRoles)) {
            return [
                'message' => 'Role tidak bisa admin',
            ];
        }

        $resultUser = $user;

        if (!$resultUser) {
            $resultUser = User::create($data);
        } else {
            $resultUser->fill($data);

            if ($resultUser->isDirty()) {
                $resultUser->save();
            }
        }

        return [
            'message' => 'User berhasil ' . ($user ? 'diperbarui' : 'dibuat'),
        ];
    }

    /**
     * Ambil user berdasarkan ID
     */
    public static function getUser(int|string $id, array $select = ['*']): User
    {
        return User::query()
            ->select($select)
            ->findOrFail($id);
    }
}
