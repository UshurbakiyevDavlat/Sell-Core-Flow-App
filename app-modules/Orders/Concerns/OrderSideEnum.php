<?php

namespace AppModules\Orders\Concerns;

use App\Concerns\Traits\HasKeys;

enum OrderSideEnum: string
{
    use HasKeys;

    case Buy = 'buy';
    case Sell = 'sell';
}
