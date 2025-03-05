<?php

namespace AppModules\Auth\Concerns\Enums\Auth;

use App\Concerns\Traits\HasKeys;

enum UserStatusEnum: string
{
    use HasKeys;
    case Active = 'active';
}
