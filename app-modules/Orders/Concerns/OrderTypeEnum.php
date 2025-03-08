<?php

namespace AppModules\Orders\Concerns;

use App\Concerns\Traits\HasKeys;

enum OrderTypeEnum: string
{
    use HasKeys;

    case Market = 'market';
    case Limit = 'limit';
}
