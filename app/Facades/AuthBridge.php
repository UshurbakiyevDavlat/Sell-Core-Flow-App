<?php

namespace App\Facades;

use App\Contracts\Auth\UserServiceInterface;
use App\Contracts\AuthBridgeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static UserServiceInterface users()
 */
class AuthBridge extends Facade
{
    protected static function getFacadeAccessor(): string
    {
       return AuthBridgeInterface::class;
    }
}
