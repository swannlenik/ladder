<?php

declare(strict_types=1);


namespace App\Http\Services;


use App\Models\AccessRights;
use App\Models\User;
use Illuminate\Support\Collection;

class UsersService
{
    public function getAllUsers(): Collection {
        return User::all();
    }

    public function findById(int $userID): User {
        return User::find($userID);
    }

    public function updateUser(int $userID, string $role, string $password = null): User {
        $user = User::find($userID);
        if ($user->role !== $role) {
            $user->role = $role;
            AccessRights::createByRole($userID, $role);
        }
        if (isset($password)) {
            $user->password = $password;
        }
        $user->save();
        return $user;
    }
}
