<?php

namespace App\Concerns\Traits;

use BackedEnum;

/** @mixin BackedEnum */
trait HasKeys
{
    /** @return array<string> */
    public static function keys(): array
    {
        return array_map(fn(self $enum) => $enum->name, self::cases());
    }
}
