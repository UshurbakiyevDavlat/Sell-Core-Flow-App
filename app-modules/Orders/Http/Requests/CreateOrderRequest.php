<?php

namespace AppModules\Orders\Http\Requests;

use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'type' => ['required', Rule::in(OrderTypeEnum::cases())],
            'side' => ['required', Rule::in(OrderSideEnum::cases())],
            'price' => ['nullable', 'numeric', 'min:0', 'required_if:type,limit'],
            'quantity' => ['required', 'numeric', 'min:0.000001'],
            //todo Добавить торговый мод, пепер трейдинг или реальная торговля.
        ];
    }
}
