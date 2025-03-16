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
    public function execute(array $marketData, float $quantity): ?array
    {
        if (count($marketData) < 200) {
            return null; // Недостаточно данных для расчета
        }

        // Извлекаем только закрывающиеся цены (`4. close`) в хронологическом порядке
        $closingPrices = array_map(fn ($data) => (float) $data['4. close'], array_values($marketData));

        // Вычисляем SMA(50) и SMA(200)
        $sma50 = array_sum(array_slice($closingPrices, -50)) / 50;
        $sma200 = array_sum(array_slice($closingPrices, -200)) / 200;

        // Получаем последнюю цену закрытия актива
        $lastPrice = end($closingPrices);

        // Проверяем пересечение вверх (сигнал на покупку)
        if ($sma50 > $sma200) {
            return [
                'side' => OrderSideEnum::Buy,
                'price' => $lastPrice,
                'quantity' => $quantity,
            ];
        }

        // Проверяем пересечение вниз (сигнал на продажу)
        if ($sma50 < $sma200) {
            return [
                'side' => OrderSideEnum::Sell,
                'price' => $lastPrice,
                'quantity' => $quantity,
            ];
        }

        return null;
    }
}
