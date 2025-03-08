<?php

namespace AppModules\Orders\Http\Resources;

use AppModules\Orders\DTO\OrderDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderDTO */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'asset_id' => $this->assetId,
            'type' => $this->type,
            'side' => $this->side,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
