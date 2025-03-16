<?php

namespace AppModules\Assets\Console\Commands;

use AppModules\Assets\Factories\MarketDataProviderFactory;
use AppModules\Assets\Repositories\AssetRepository;
use Illuminate\Console\Command;

class UpdateAssetPricesCommand extends Command
{
    protected $signature = 'assets:update-prices';

    public function __construct(
        protected MarketDataProviderFactory $providerFactory,
        protected AssetRepository $repository
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $symbols = $this->repository->getAllSymbols();
        $provider = $this->providerFactory->provider();

        foreach ($symbols as $symbol) {
            $price = $provider->getPrice($symbol);
            $this->repository->updatePriceBySymbol($symbol, $price);
            $this->info("Asset: $symbol price updated on $price");
        }

        $this->info('Asset prices updated successfully!');
    }
}
