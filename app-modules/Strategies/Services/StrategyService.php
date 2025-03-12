<?php

namespace AppModules\Strategies\Services;

use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Services\OrderService;
use AppModules\Strategies\Factories\StrategyFactory;
use Exception;

class StrategyService
{
    public function __construct(
        protected OrderService $orderService,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function runStrategy(
        string $strategyName,
        array  $marketData,
        ?float $previousSma50 = null,
        ?float $previousSma200 = null
    ): ?OrderDTO
    {
        $strategy = StrategyFactory::make($strategyName);
        $order = $strategy->execute($marketData, $previousSma50, $previousSma200);

        if (!$order) {
            dump("❌ Strategy '$strategyName' was not successful.\n");
            return null;
        }

        $this->orderService->create($order->toArray());
        dump("✅ Strategy '$strategyName' generated a trade.\n");
        return $order;
    }

}
