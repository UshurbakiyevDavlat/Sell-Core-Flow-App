<?php

namespace AppModules\Strategies\Factories;

use AppModules\Strategies\Concerns\StrategyTypesEnum;
use AppModules\Strategies\Contracts\StrategyInterface;
use AppModules\Strategies\Types\RsiStrategy;
use AppModules\Strategies\Types\SmaCrossoverStrategy;

class StrategyFactory
{
    public static function make(StrategyTypesEnum $strategy): StrategyInterface
    {
        return match ($strategy) {
            StrategyTypesEnum::Sma => new SmaCrossoverStrategy,
            StrategyTypesEnum::Rsi => new RsiStrategy,
        };
    }
}
