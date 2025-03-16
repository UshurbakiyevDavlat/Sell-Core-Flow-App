<?php

namespace AppModules\Strategies\Concerns;

enum StrategyTypesEnum: string
{
    case Sma = 'sma_crossover';
    case Rsi = 'rsi';
}
