<?php

namespace AppModules\Assets\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetPriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
