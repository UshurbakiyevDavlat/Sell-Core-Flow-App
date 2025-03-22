<?php

namespace AppModules\Trades\Listeners;

use AppModules\Billing\Services\BillingService;
use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderTradeModeEnum;
use AppModules\Orders\Services\OrderService;
use AppModules\Trades\Events\TradeCreatedEvent;
use AppModules\Trades\Services\TradesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleTradeCreatedListener implements ShouldQueue
{
    // todo do not forget about bridges, it will reduce complexity
    public function __construct(
        protected TradesService $tradesService,
        protected BillingService $billingService,
        protected OrderService $orderService,
    ) {}

    public function handle(TradeCreatedEvent $event): void
    {
        $trade = $event->tradeDto;
        $tradeOrder = $this->orderService->getById($trade->orderId);

        if ($tradeOrder->tradeMode === OrderTradeModeEnum::Backtest) {
            return; // В режиме Backtest мы не создаем реальный трейд и не меняем баланс
        }

        if ($this->tradesService->executeTrade($trade->id)) {
            if ($tradeOrder->side === OrderSideEnum::Sell) {
                // Если ордер трейда на продажу, начисляем деньги пользователю
                $this->billingService->profit($trade->userId, $trade->id);
                //todo broadcasting
            }
        }
    }
}
