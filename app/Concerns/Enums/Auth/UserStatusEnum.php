<?php

namespace App\Concerns\Enums\Auth;

use App\Concerns\Traits\HasKeys;

enum UserStatusEnum: string
{
    use HasKeys;
    case Active = 'active';
}
