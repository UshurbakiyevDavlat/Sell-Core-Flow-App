<?php

namespace AppModules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    protected function validationRules(): array
    {
        return [
          'email' => ['required', 'email', 'exists:users,email'],
          'password' => ['required'],
        ];
    }
}
