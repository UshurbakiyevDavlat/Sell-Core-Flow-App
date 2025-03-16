<?php

namespace AppModules\Auth\Observers;

use AppModules\Auth\Models\User;
use AppModules\Billing\Repositories\BillingRepository;

class UserObserver
{
    public function created(User $user): void
    {
        // todo make bridge for it..
        app(BillingRepository::class)->createUserBalance($user->id);
    }
}
