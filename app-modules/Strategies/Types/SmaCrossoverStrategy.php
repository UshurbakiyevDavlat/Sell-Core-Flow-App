<?php

namespace AppModules\Strategies\Types;

use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Strategies\Contracts\StrategyInterface;

/**
 * Простая стратегия, использующая скользящие средние (SMA):
 *
 * SMA(50) → Короткая средняя (более чувствительная к изменениям).
 * SMA(200) → Долгая средняя (отражает долгосрочный тренд).
 *
 * Если SMA(50) пересекает SMA(200) СНИЗУ ВВЕРХ → Бычий сигнал (покупка).
 * Если SMA(50) пересекает SMA(200) СВЕРХУ ВНИЗ → Медвежий сигнал (продажа).
 */
class SmaCrossoverStrategy implements StrategyInterface
{
    public function execute(array $marketData, ?float $previousSma50 = null, ?float $previousSma200 = null): ?OrderDTO
    {
        if (count($marketData) < 200) {
            dump('Not enough data to crossover');
            return null;
        }

        // Вычисляем SMA(50) и SMA(200)
        $sma50 = array_sum(array_slice($marketData, -50)) / 50;
        $sma200 = array_sum(array_slice($marketData, -200)) / 200;

        print('SMA50: ' . $sma50 . PHP_EOL);
        print('SMA200: ' . $sma200 . PHP_EOL);

        print('Previous SMA50: ' . $previousSma50 . PHP_EOL);
        print('Previous SMA200: ' . $previousSma200 . PHP_EOL);

        if ($previousSma50 !== null && $previousSma200 !== null) {
            // Проверяем пересечение вверх (сигнал на покупку)
            if ($previousSma50 < $previousSma200 && $sma50 > $sma200) {
                dump("📈 SMA Crossover UP! (Buy Signal)" . PHP_EOL);
                return $this->createOrder(OrderSideEnum::Buy, end($marketData));
            }

            // Проверяем пересечение вниз (сигнал на продажу)
            if ($previousSma50 > $previousSma200 && $sma50 < $sma200) {
                dump("📉 SMA Crossover DOWN! (Sell Signal)" . PHP_EOL);
                return $this->createOrder(OrderSideEnum::Sell, end($marketData));
            }
        }

        return null;
    }

    private function createOrder(OrderSideEnum $side, float $price): OrderDTO
    {
        return new OrderDTO(
            id: 100,
            userId: 1, // todo: передавать реальные данные
            assetId: 1,
            type: OrderTypeEnum::Limit,
            side: $side,
            price: $price,
            quantity: 1,
            status: OrderStatusEnum::Pending,
            createdAt: now()->toDateTimeString()
        );
    }
}
