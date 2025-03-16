<?php

namespace AppModules\Strategies\Contracts;

interface StrategyInterface
{
    public function execute(array $marketData, float $quantity): ?array;
}
