<?php

namespace AppModules\Strategies\Types;

use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Strategies\Contracts\StrategyInterface;

/**
 * Индикатор относительной силы (RSI) измеряет, перекуплен ли актив (слишком дорогой) или перепродан (слишком дешёвый).
 */
class RsiStrategy implements StrategyInterface
{
    private const int RSI_PERIOD = 14; // Период для расчета RSI

    private const int RSI_OVERSOLD = 30; // Уровень перепроданности (покупаем)

    private const int RSI_OVERBOUGHT = 70; // Уровень перекупленности (продаём)

    private const float PRICE_TOLERANCE = 0.98; // Допуск на изменение цены (2%)

    public function execute(array $marketData, float $quantity): ?array
    {
        $result['type'] = OrderTypeEnum::Market;

        if (count($marketData) < self::RSI_PERIOD + 1) {
            return null; // Недостаточно данных для расчета
        }

        [$rsi, $lastPrice, $prevPrice, $prevPrevPrice] = $this->calculateRsi($marketData);

        // 1️⃣ **BUY (Покупка) при RSI < 30**
        if (
            $rsi < self::RSI_OVERSOLD
            && (
                $lastPrice >= ($prevPrice * self::PRICE_TOLERANCE)
                ||
                $lastPrice > $prevPrevPrice
            )
        ) {
            $result['side'] = OrderSideEnum::Buy;
            $result['price'] = (float) $lastPrice;
            $result['quantity'] = (float) $quantity;

            return $result;
        }

        // 2️⃣ **SELL (Продажа) при RSI > 70**
        if (
            $rsi > self::RSI_OVERBOUGHT
            && (
                $lastPrice <= ($prevPrice * (2 - self::PRICE_TOLERANCE))
                ||
                $lastPrice < $prevPrevPrice
            )
        ) {
            $result['side'] = OrderSideEnum::Sell;
            $result['price'] = (float) $lastPrice;
            $result['quantity'] = (float) $quantity;

            return $result;
        }

        return null;
    }

    private function calculateRsi(array $marketData): array
    {
        $prices = array_values(array_map(fn ($day) => (float) $day['4. close'], $marketData));
        $period = self::RSI_PERIOD;
        $gains = 0;
        $losses = 0;

        for ($i = 1; $i <= $period; $i++) {
            $change = $prices[$i] - $prices[$i - 1];
            if ($change > 0) {
                $gains += $change;
            } else {
                $losses += abs($change);
            }
        }

        // Средний прирост и убыток (Исправленный)
        $avgGain = ($gains > 0) ? $gains / $period : 0.0001;
        $avgLoss = ($losses > 0) ? $losses / $period : 0.0001;

        // Рассчитываем RSI
        $rs = $avgGain / $avgLoss;
        $rsi = 100 - (100 / (1 + $rs));

        // Берём текущую и предыдущие цены для определения тренда
        $lastPrice = end($prices);
        $prevPrice = $prices[count($prices) - 2];
        $prevPrevPrice = $prices[count($prices) - 3];

        return [$rsi, $lastPrice, $prevPrice, $prevPrevPrice];
    }
}
