<?php

namespace AppModules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingProfitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'trade_id' => ['required', 'integer', 'exists:trades,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'trade_id.required' => 'ID трейда обязателен.',
            'trade_id.exists' => 'Трейда с таким ID не существует.',
        ];
    }
}
