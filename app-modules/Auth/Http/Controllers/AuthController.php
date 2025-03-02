<?php

namespace AppModules\Auth\Http\Controllers;

use AppModules\Auth\Services\AuthService;
use Illuminate\Http\Request;

readonly class AuthController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request) //todo add request classes
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return response()->json($this->authService->register($request->all())); //todo add resources
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return response()->json($this->authService->login($request->only('email', 'password')));
    }
}
