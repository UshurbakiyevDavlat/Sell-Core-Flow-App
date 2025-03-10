<?php

namespace AppModules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingDepositRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Сумма обязательна для пополнения.',
            'amount.numeric' => 'Сумма должна быть числом.',
            'amount.min' => 'Минимальная сумма пополнения — 1.',
        ];
    }
}
