<?php

namespace AppModules\Trades\Http\Resources;

use AppModules\Trades\DTO\TradesDTO;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TradesDTO */
class TradesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'orderId' => $this->orderId,
            'userId' => $this->userId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
        ];
    }
}
