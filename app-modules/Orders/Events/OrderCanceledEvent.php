<?php

namespace AppModules\Orders\Events;

use AppModules\Orders\DTO\OrderDTO;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCanceledEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public OrderDTO $order)
    {
    }
}
