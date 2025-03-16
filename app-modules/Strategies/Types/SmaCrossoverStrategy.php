<?php

namespace AppModules\Strategies\Types;

use AppModules\Orders\Concerns\OrderSideEnum;
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
    public function execute(
        array $marketData,
        float $quantity,
        ?float $previousSma50 = null,
        ?float $previousSma200 = null
    ): ?array {
        if (count($marketData) < 200) {
            return null; // Недостаточно данных для расчета
        }

        // Вычисляем SMA(50) и SMA(200)
        $sma50 = array_sum(array_slice($marketData, -50)) / 50;
        $sma200 = array_sum(array_slice($marketData, -200)) / 200;

        // Проверяем пересечение вверх (сигнал на покупку)
        if ($previousSma50 !== null && $previousSma200 !== null) {
            if ($previousSma50 < $previousSma200 && $sma50 > $sma200) {
                return [
                    'side' => OrderSideEnum::Buy,
                    'price' => end($marketData),
                    'quantity' => $quantity,
                ];
            }

            // Проверяем пересечение вниз (сигнал на продажу)
            if ($previousSma50 > $previousSma200 && $sma50 < $sma200) {
                return [
                    'side' => OrderSideEnum::Sell,
                    'price' => end($marketData),
                    'quantity' => $quantity,
                ];
            }
        }

        return null;
    }
}
