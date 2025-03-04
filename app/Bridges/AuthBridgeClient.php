<?php

namespace App\Bridges;

use App\Contracts\Auth\UserServiceInterface;
use App\Contracts\AuthBridgeInterface;

class AuthBridgeClient implements AuthBridgeInterface
{
    public function users(): UserServiceInterface
    {
        return app(UserServiceInterface::class);
    }
}
