<?php

namespace AppModules\Assets\Contracts;

interface MarketDataProviderInterface
{
    public function getAssetList(): array;

    public function getPrice(string $symbol): float;

    public function getHistoricalData(string $symbol, string $interval = 'daily', int $outputSize = 100): array;
}
