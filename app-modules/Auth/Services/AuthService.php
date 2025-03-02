<?php

namespace AppModules\Auth\Services;

use AppModules\Auth\Repositories\UserRepository;
use AppModules\Auth\DTO\UserDTO;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $userDTO = $this->userRepository->create($data);

        return [
            'user' => $userDTO,
            'token' => $this->generateToken($userDTO)
        ];
    }

    public function login(array $credentials): array
    {
        $userDTO = $this->userRepository->findByEmail($credentials['email']);

        if (!$userDTO || !password_verify($credentials['password'], $userDTO->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return [
            'user' => $userDTO,
            'token' => $this->generateToken($userDTO)
        ];
    }

    protected function generateToken(UserDTO $userDTO): string
    {
        return PersonalAccessToken::createToken($userDTO->id, ['*'])->plainTextToken;
    }
}

