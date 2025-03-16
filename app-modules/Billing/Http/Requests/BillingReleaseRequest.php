<?php

namespace AppModules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingReleaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'ID ордера обязателен.',
            'order_id.exists' => 'Ордера с таким ID не существует.',
        ];
    }
}
