<?php

namespace AppModules\Auth\Http\Controllers;

use AppModules\Auth\Http\Requests\LoginRequest;
use AppModules\Auth\Http\Requests\RegisterRequest;
use AppModules\Auth\Http\Resources\UserResource;
use AppModules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

readonly class AuthController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userData = $this->authService->register($data);

        return response()->json([
            'user' => new UserResource($userData['user']),
            'token' => $userData['token']
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userData = $this->authService->login($data);

        return response()->json([
            'user' => new UserResource($userData['user']),
            'token' => $userData['token'],
        ]);
    }
}
