<?php

namespace App\Bridges\Auth;

use App\Contracts\Auth\UserServiceInterface;
use AppModules\Auth\DTO\UserDTO;

class UserService implements UserServiceInterface
{
    public function getUserById(int $id): ?UserDTO
    {
        return app(\AppModules\Auth\Services\UserService::class)->getUserById($id);
    }

    public function getUserByEmail(string $email): ?UserDTO
    {
        return app(\AppModules\Auth\Services\UserService::class)->getUserByEmail($email);
    }
}
