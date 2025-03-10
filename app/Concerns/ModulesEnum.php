<?php

namespace App\Concerns;

use App\Concerns\Traits\HasKeys;

enum ModulesEnum: string
{
    use HasKeys;

    case Auth = 'Auth';
    case Assets = 'Assets';
    case Orders = 'Orders';
    case Trades = 'Trades';
    case Billing = 'Billing';

    public static function getEnabledModules(): array
    {
        return [
            self::Auth->value,
            self::Assets->value,
            self::Orders->value,
            self::Trades->value,
            self::Billing->value,
        ];
    }
}
