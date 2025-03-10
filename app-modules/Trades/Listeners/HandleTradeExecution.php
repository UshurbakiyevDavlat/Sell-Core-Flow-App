<?php

namespace AppModules\Trades\Listeners;

use AppModules\Trades\Events\TradeCreated;
use AppModules\Trades\Services\TradesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTradeExecution implements ShouldQueue
{
    public function __construct(protected TradesService $tradesService)
    {
    }

    public function handle(TradeCreated $event): void
    {
        $this->tradesService->executeTrade($event->tradeDto->id);
    }
}
