<?php

namespace AppModules\Strategies\Services;

use AppModules\Assets\Services\AssetService;
use AppModules\Auth\Services\UserService;
use AppModules\Billing\Services\BillingService;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Services\OrderService;
use AppModules\Strategies\Concerns\StrategyStatusEnum;
use AppModules\Strategies\Concerns\StrategyTypesEnum;
use AppModules\Strategies\Factories\StrategyFactory;
use Exception;

class StrategyService
{
    public function __construct(
        protected OrderService $orderService,
        protected UserService $userService,
        protected AssetService $assetService,
        protected BillingService $billingService,
        protected StrategyRunService $strategyRunService
    ) {}

    /**
     * Запуск стратегии с реальными данными
     *
     * @throws Exception
     */
    public function runStrategy(
        StrategyTypesEnum $strategyName,
        int $userId,
        int $assetId,
        float $quantity,
    ): ?OrderDTO {
        $strategy = StrategyFactory::make($strategyName);
        $marketData = $this->assetService->getHistoricalData($assetId);

        if (empty($marketData)) {
            $this->strategyRunService->logRun([
                'user_id' => $userId,
                'strategy' => $strategyName->value,
                'asset_id' => $assetId,
                'quantity' => $quantity,
                'status' => StrategyStatusEnum::Failed->value,
                'message' => 'Нет рыночных данных',
            ]);

            return null;
        }

        $orderData = $strategy->execute($marketData, $quantity);
        if (! $orderData) {
            $this->strategyRunService->logRun([
                'user_id' => $userId,
                'strategy' => $strategyName->value,
                'asset_id' => $assetId,
                'quantity' => $quantity,
                'status' => StrategyStatusEnum::Failed->value,
                'message' => 'Нет торгового сигнала',
            ]);

            return null;
        }

        // Добавляем userId и assetId
        $orderData['user_id'] = $userId;
        $orderData['asset_id'] = $assetId;

        $order = $this->orderService->create($orderData);

        if (! $this->billingService->charge($userId, $order->id)) {
            $this->strategyRunService->logRun([
                'user_id' => $userId,
                'strategy' => $strategyName->value,
                'asset_id' => $assetId,
                'quantity' => $quantity,
                'status' => StrategyStatusEnum::Failed->value,
                'message' => 'Недостаточно средств',
            ]);

            return null;
        }

        // Логируем успешный запуск стратегии
        $this->strategyRunService->logRun([
            'user_id' => $userId,
            'strategy' => $strategyName->value,
            'asset_id' => $assetId,
            'quantity' => $quantity,
            'status' => StrategyStatusEnum::Success->value,
            'message' => 'Ордер создан успешно',
        ]);

        return $order;
    }

    /**
     * Запуск стратегий для конкретного пользователя
     */
    public function runUserStrategies(
        int $userId,
        array $strategies,
        array $assetIds,
        float $quantity
    ): void {
        foreach ($assetIds as $assetId) {
            /** @var StrategyTypesEnum $strategy */
            foreach ($strategies as $strategy) {
                try {
                    $this->runStrategy($strategy, $userId, $assetId, $quantity);
                } catch (Exception $e) {
                    $this->strategyRunService->logRun([
                        'user_id' => $userId,
                        'strategy' => $strategy->value,
                        'asset_id' => $assetId,
                        'quantity' => $quantity,
                        'status' => StrategyStatusEnum::Failed->value,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }
}
