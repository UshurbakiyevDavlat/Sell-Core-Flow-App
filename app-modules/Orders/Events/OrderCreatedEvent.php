<?php

namespace AppModules\Orders\Events;

use AppModules\Orders\DTO\OrderDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCreatedEvent implements ShouldBroadcast
{
    public function __construct(
       public OrderDTO $order,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('orders'); // Публичный канал
    }

    public function broadcastWith(): array
    {
        return [
            'order' => $this->order,
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
