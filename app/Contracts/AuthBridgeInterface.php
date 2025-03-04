<?php

namespace App\Contracts;

use App\Contracts\Auth\UserServiceInterface;

interface AuthBridgeInterface
{
    public function users(): UserServiceInterface;
}
