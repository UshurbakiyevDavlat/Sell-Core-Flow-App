<?php

namespace AppModules\Orders\Concerns;

use App\Concerns\Traits\HasKeys;

enum OrderStatusEnum: string
{
    use HasKeys;

    case Pending = 'pending';
    case Executed = 'executed';
    case Cancelled = 'cancelled';
}
