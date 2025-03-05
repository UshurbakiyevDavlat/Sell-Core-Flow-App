<?php

namespace AppModules\Assets\Http\Resources;

use AppModules\Assets\DTO\AssetDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AssetDTO */
class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'name' => $this->name,
            'type' => $this->type,
            'price' => $this->price,
            'created_at' => $this->createdAt->toDateTimeString(),
        ];
    }
}
