<?php

namespace AppModules\Assets\Contracts;

use AppModules\Assets\Concerns\Enums\AssetIntervalEnum;

interface MarketDataProviderInterface
{
    public function getAssetList(): array;

    public function getPrice(string $symbol): float;

    public function getHistoricalData(
        string $symbol,
        AssetIntervalEnum $interval = AssetIntervalEnum::Daily,
        int $outputSize = 100
    ): array;
}
