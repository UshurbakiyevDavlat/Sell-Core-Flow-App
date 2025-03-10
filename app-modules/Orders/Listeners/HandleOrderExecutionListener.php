<?php

namespace AppModules\Orders\Listeners;

use AppModules\Billing\Services\BillingService;
use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Events\OrderExecutedEvent;

class HandleOrderExecutionListener
{
    public function __construct(protected BillingService $billingService)
    {
    }

    public function handle(OrderExecutedEvent $event): void
    {
        $order = $event->order;

        if ($order->side === OrderSideEnum::Buy) {
            // Если ордер на покупку, списываем деньги
            $this->billingService->charge($order->userId, $order->id);
        }

        // Если ордер на продажу, деньги НЕ списываем, но активы резервируем
        // Здесь ничего не делаем, а прибыль начислим после трейда
    }
}

