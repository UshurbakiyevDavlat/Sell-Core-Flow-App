<?php

namespace AppModules\Orders\Listeners;

use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Trades\Concerns\Enums\TradeStatusEnum;
use AppModules\Trades\Services\TradesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTradeFromOrderListener implements ShouldQueue
{
    public function __construct(protected TradesService $tradesService)
    {
    }

    public function handle(OrderExecutedEvent $event): void
    {
        //todo need to make bridge and do this through it.
        $this->tradesService->createTrade([
            'order_id' => $event->order->id,
            'user_id' => $event->order->userId,
            'price' => $event->order->price,
            'quantity' => $event->order->quantity,
            'status' => TradeStatusEnum::Pending,
        ]);
    }
}
