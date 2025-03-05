<?php

namespace AppModules\Assets\Concerns\Enums;

use App\Concerns\Traits\HasKeys;

enum AssetTypeEnum: string
{
    use HasKeys;

    case Stock = 'stock';
    case Crypto = 'crypto';
    case Forex = 'forex';
}
