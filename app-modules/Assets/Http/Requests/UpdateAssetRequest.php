<?php

namespace AppModules\Assets\Http\Requests;

use AppModules\Assets\Concerns\Enums\AssetTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'symbol' => ['required', 'string', Rule::unique('assets', 'symbol'), 'max:10'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', Rule::in(AssetTypeEnum::cases())],
        ];
    }
}
