<?php

namespace AppModules\Assets\Services;

use AppModules\Assets\Concerns\Enums\AssetIntervalEnum;
use AppModules\Assets\Concerns\Enums\AssetTypeEnum;
use AppModules\Assets\DTO\AssetDTO;
use AppModules\Assets\Events\AssetPriceUpdated;
use AppModules\Assets\Factories\MarketDataProviderFactory;
use AppModules\Assets\Repositories\AssetRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka;

readonly class AssetService
{
    private const int CACHE_TTL = 86400; // 24 часа в секундах

    public function __construct(
        private AssetRepository $repository,
        private MarketDataProviderFactory $providerFactory
    ) {}

    public function getAll(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getAll($perPage);
    }

    public function getById(int $id): ?AssetDTO
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): AssetDTO
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): ?AssetDTO
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @throws Exception
     */
    public function updatePrice(int $id, float $newPrice): void
    {
        DB::transaction(function () use ($id, $newPrice) {
            $this->repository->update($id, ['price' => $newPrice]);

            broadcast(new AssetPriceUpdated($id, $newPrice));

            Kafka::publish()
                ->onTopic('asset_price_update')
                ->withBody([
                    'asset_id' => $id,
                    'price' => $newPrice,
                ])
                ->send();
        });
    }

    /**
     * @throws Exception
     */
    public function updateAllPrices(): void
    {
        $provider = $this->providerFactory->provider();
        $symbols = $this->repository->getAllSymbols();

        foreach ($symbols as $symbol) {
            $newPrice = $provider->getPrice($symbol);

            $this->repository->updatePriceBySymbol($symbol, $newPrice);

            Kafka::publish()
                ->onTopic('asset_price_update')
                ->withBody([
                    'symbol' => $symbol,
                    'price' => $newPrice,
                ])
                ->send();
        }
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @throws Exception
     */
    public function initializeAssets(): void
    {
        $provider = $this->providerFactory->provider();
        $assets = $provider->getAssetList();

        if (empty($assets)) {
            throw new Exception('Asset list is empty');
        }

        foreach ($assets as $assetData) {
            if ($this->repository->exists($assetData['symbol'])) {
                continue;
            }
            $this->repository->create([
                'symbol' => $assetData['symbol'],
                'name' => $assetData['name'],
                'type' => strtolower($assetData['assetType'] ?? AssetTypeEnum::Stock),
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function getHistoricalData(
        int $assetId,
        AssetIntervalEnum $interval = AssetIntervalEnum::Daily,
        int $outputSize = 100
    ): array {
        $asset = $this->repository->getById($assetId);
        if (empty($asset)) {
            throw new Exception('Asset not found');
        }

        $provider = $this->providerFactory->provider();

        return $provider->getHistoricalData($asset->symbol, $interval, $outputSize);
    }
}
