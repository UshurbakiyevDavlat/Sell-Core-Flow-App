<?php

namespace AppModules\Orders\Events;

use AppModules\Orders\DTO\OrderDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCanceledEvent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public OrderDTO $order) {}

    public function broadcastOn(): Channel
    {
        return new Channel('orders');
    }

    public function broadcastWith(): array
    {
        return [
            'order' => $this->order,
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.canceled';
    }
}
