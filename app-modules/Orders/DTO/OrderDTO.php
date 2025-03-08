<?php

namespace AppModules\Orders\DTO;

use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\Models\Order;

readonly class OrderDTO
{
    public function __construct(
        public int             $id,
        public int             $userId,
        public int             $assetId,
        public OrderTypeEnum   $type,
        public OrderSideEnum   $side,
        public ?float          $price,
        public float           $quantity,
        public OrderStatusEnum $status,
        public string          $createdAt,
    )
    {
    }

    public static function fromModel(?Order $order): ?self
    {
        if (!$order) {
            return null;
        }

        return new self(
            id: $order->id,
            userId: $order->user_id,
            assetId: $order->asset_id,
            type: $order->type,
            side: $order->side,
            price: $order->price,
            quantity: $order->quantity,
            status: $order->status,
            createdAt: $order->created_at->toDateTimeString(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'asset_id' => $this->assetId,
            'type' => $this->type->value,
            'side' => $this->side->value,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status->value,
            'createdAt' => $this->createdAt,
        ];
    }
}
