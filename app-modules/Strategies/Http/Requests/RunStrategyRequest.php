<?php

namespace AppModules\Strategies\Http\Requests;

use AppModules\Strategies\Concerns\StrategyTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RunStrategyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'], 'required|integer|exists:users,id',
            'strategies' => ['required', 'array', 'min:1', Rule::in(StrategyTypesEnum::cases())],
            'asset_ids' => ['required', 'array', 'min:1'],
            'asset_ids.*' => ['integer', 'exists:assets,id'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
        ];
    }
}
