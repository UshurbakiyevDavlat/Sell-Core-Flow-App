<?php

namespace AppModules\Auth\Repositories;

use App\Concerns\Enums\Auth\UserStatusEnum;
use AppModules\Auth\DTO\UserDTO;
use AppModules\Auth\Models\User;

class UserRepository
{
    public function findByEmail(string $email): ?UserDTO
    {
        $user = User::where('email', $email)->first();
        return $user ? UserDTO::fromModel($user) : null;
    }

    public function findById(int $userId): ?UserDTO
    {
        $user = User::find($userId);
        return $user ? UserDTO::fromModel($user) : null;
    }

    public function create(array $data): UserDTO
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => UserStatusEnum::Active,
        ]);

        return UserDTO::fromModel($user);
    }
}
