<?php

namespace AppModules\Trades\Http\Requests;

use AppModules\Trades\Concerns\Enums\TradeStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTradesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'user_id' => ['required', 'exists:users,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(TradeStatusEnum::cases())],
        ];
    }

    public function messages(): array
    {
        // todo Опубликовать для всех модулей реквест месседжи и добавить туда переводы
        return [
            'order_id.required' => 'Поле order_id обязательно.',
            'order_id.integer' => 'Поле order_id должно быть числом.',
            'order_id.exists' => 'Указанный order_id не найден в базе.',
            'user_id.required' => 'Поле user_id обязательно.',
            'user_id.integer' => 'Поле user_id должно быть числом.',
            'user_id.exists' => 'Указанный user_id не найден в базе.',
            'price.required' => 'Поле price обязательно.',
            'price.numeric' => 'Поле price должно быть числом.',
            'price.min' => 'Цена не может быть отрицательной.',
            'quantity.required' => 'Поле quantity обязательно.',
            'quantity.integer' => 'Поле quantity должно быть целым числом.',
            'quantity.min' => 'Количество должно быть не менее 1.',
            'status.required' => 'Поле status обязательно.',
            'status.string' => 'Поле status должно быть строкой.',
            'status.in' => 'Неверный статус. Доступные значения: pending, executed, canceled.',
        ];
    }
}
