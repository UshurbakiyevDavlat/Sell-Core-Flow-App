<?php

namespace App\Contracts\Auth;

use AppModules\Auth\DTO\UserDTO;

interface UserServiceInterface
{
    public function getUserById(int $id): ?UserDTO;

    public function getUserByEmail(string $email): ?UserDTO;
}
