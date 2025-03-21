<?php

namespace AppModules\Assets\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssetPriceUpdated implements ShouldBroadcast
{
    public function __construct(
        public string $assetId,
        public float $price,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('assets'); // Публичный канал
    }

    public function broadcastWith(): array
    {
        return [
            'assetId' => $this->assetId,
            'price' => $this->price,
        ];
    }

    public function broadcastAs(): string
    {
        return 'price.updated';
    }
}
