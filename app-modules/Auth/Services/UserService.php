<?php

namespace AppModules\Auth\Services;

use AppModules\Auth\DTO\UserDTO;
use AppModules\Auth\Repositories\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function getUserById(int $userId): ?UserDTO
    {
        return $this->userRepository->findById($userId);
    }

    public function getUserByEmail(string $email): ?UserDTO
    {
        return $this->userRepository->findByEmail($email);
    }
}
