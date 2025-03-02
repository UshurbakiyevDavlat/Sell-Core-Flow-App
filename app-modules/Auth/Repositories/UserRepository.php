<?php

namespace AppModules\Auth\Repositories;

use AppModules\Auth\DTO\UserDTO;
use AppModules\Auth\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function findByEmail(string $email): ?UserDTO
    {
        $user = User::where('email', $email)->first();
        return $user ? UserDTO::fromModel($user) : null;
    }

    public function findById(int $id): ?UserDTO
    {
        $user = User::find($id);
        return $user ? UserDTO::fromModel($user) : null;
    }

    public function create(array $data): UserDTO
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), //todo add eloquent auto casting
            'status' => 'active' //todo add enums
        ]);

        return UserDTO::fromModel($user);
    }
}
