<?php

namespace AppModules\Strategies\Contracts;

use AppModules\Orders\DTO\OrderDTO;

interface StrategyInterface
{
    public function execute(array $marketData): ?OrderDTO;
}
