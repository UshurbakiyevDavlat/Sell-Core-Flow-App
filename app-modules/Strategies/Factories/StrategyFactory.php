<?php

namespace AppModules\Strategies\Factories;

use AppModules\Strategies\Contracts\StrategyInterface;
use AppModules\Strategies\Types\RsiStrategy;
use AppModules\Strategies\Types\SmaCrossoverStrategy;
use InvalidArgumentException;

class StrategyFactory
{
    public static function make(string $strategyName): StrategyInterface
    {
        return match ($strategyName) {
            'sma_crossover' => new SmaCrossoverStrategy(),
            'rsi' => new RsiStrategy(),
            default => throw new InvalidArgumentException('Strategy not supported: ' . $strategyName),
        };
    }
}
