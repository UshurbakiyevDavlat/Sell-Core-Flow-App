<?php

namespace AppModules\Trades\DTO;

use AppModules\Trades\Concerns\Enums\TradeStatusEnum;
use AppModules\Trades\Models\Trade;
use Carbon\Carbon;

readonly class TradesDTO
{
    public function __construct(
        public int             $id,
        public int             $orderId,
        public int             $userId,
        public float           $price,
        public int             $quantity,
        public TradeStatusEnum $status,
        public Carbon          $createdAt,
    )
    {
    }

    public static function fromModel(?Trade $trade): ?self
    {
        if (!$trade) {
            return null;
        }

        return new self(
            id: $trade->id,
            orderId: $trade->order_id,
            userId: $trade->user_id,
            price: $trade->price,
            quantity: $trade->quantity,
            status: $trade->status,
            createdAt: $trade->created_at,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'orderId' => $this->orderId,
            'userId' => $this->userId,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status->value,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
