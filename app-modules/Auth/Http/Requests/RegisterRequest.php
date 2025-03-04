<?php

namespace AppModules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    protected function validationRules(): array
    {
        return [
          'email' => ['required', 'email', 'unique:users,email'],
          'name' => ['required', 'string'],
          'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }
}
