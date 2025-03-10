<?php

namespace AppModules\Orders\Events;

use AppModules\Orders\DTO\OrderDTO;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderExecuted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public OrderDTO $order)
    {
    }

    public function toArray(): array
    {
        return $this->order->toArray();
    }
}
