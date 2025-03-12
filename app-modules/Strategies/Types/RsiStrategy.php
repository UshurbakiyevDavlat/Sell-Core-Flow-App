<?php

namespace AppModules\Strategies\Types;

use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
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

    public function execute(array $marketData): ?OrderDTO
    {
        if (count($marketData) < self::RSI_PERIOD + 1) {
            return null; // Недостаточно данных для расчета
        }

        [$rsi, $lastPrice, $prevPrice, $prevPrevPrice] = $this->calculateRsi($marketData);

        print("📊 RSI: $rsi | LastPrice: $lastPrice | PrevPrice: $prevPrice | PrevPrevPrice: $prevPrevPrice\n");

        // 1️⃣ **BUY (Покупка) при RSI < 30**
        if ($rsi < self::RSI_OVERSOLD && ($lastPrice >= ($prevPrice * self::PRICE_TOLERANCE) || $lastPrice > $prevPrevPrice)) {
            print("✅ RSI Buy Signal Triggered! Placing Buy Order.\n");

            return new OrderDTO(
                id: 100,
                userId: 1, // TODO: Подставить реальные данные
                assetId: 1,
                type: OrderTypeEnum::Limit,
                side: OrderSideEnum::Buy,
                price: $lastPrice,
                quantity: 1,
                status: OrderStatusEnum::Pending,
                createdAt: now()->toDateTimeString()
            );
        }

        // 2️⃣ **SELL (Продажа) при RSI > 70**
        if ($rsi > self::RSI_OVERBOUGHT && ($lastPrice <= ($prevPrice * (2 - self::PRICE_TOLERANCE)) || $lastPrice < $prevPrevPrice)) {
            print("📉 RSI Sell Signal Triggered! Placing Sell Order.\n");

            return new OrderDTO(
                id: 101,
                userId: 1, // TODO: Подставить реальные данные
                assetId: 1,
                type: OrderTypeEnum::Limit,
                side: OrderSideEnum::Sell,
                price: $lastPrice,
                quantity: 1,
                status: OrderStatusEnum::Pending,
                createdAt: now()->toDateTimeString()
            );
        }

        return null;
    }

    private function calculateRsi(array $marketData): array
    {
        $period = self::RSI_PERIOD;
        $gains = 0;
        $losses = 0;

        for ($i = 1; $i <= $period; $i++) {
            $change = $marketData[$i] - $marketData[$i - 1];
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
        $lastPrice = end($marketData);
        $prevPrice = $marketData[count($marketData) - 2];
        $prevPrevPrice = $marketData[count($marketData) - 3];

        return [$rsi, $lastPrice, $prevPrice, $prevPrevPrice];
    }
}
