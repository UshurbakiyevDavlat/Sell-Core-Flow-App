<?php

namespace AppModules\Trades\Concerns\Enums;

use App\Concerns\Traits\HasKeys;

enum TradeStatusEnum: string
{
    use HasKeys;

    case Pending = 'pending';
    case Executed = 'executed';
    case Cancelled = 'cancelled';
}
