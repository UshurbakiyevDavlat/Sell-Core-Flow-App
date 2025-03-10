<?php

namespace AppModules\Orders\Listeners;

use AppModules\Billing\Services\BillingService;
use AppModules\Orders\Events\OrderCanceledEvent;

class ReleaseOrderListener
{
    public function __construct(protected BillingService $billingService) {}

    public function handle(OrderCanceledEvent $event): void
    {
        $this->billingService->release($event->order->userId, $event->order->id);
    }
}
