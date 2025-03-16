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

        // todo хуйня какая-то, переделать так как упираемся в лимиты апи и плюс в n+1 проблему.
        // todo вытащить за один запрос данные и сделать балк апдейт, вытащить измененные айди
        // todo и отправлять айди => прайс на вебсокет.
        foreach ($symbols as $symbol) {
            $price = $provider->getPrice($symbol);
            $this->repository->updatePriceBySymbol($symbol, $price);
            $this->info("Asset: $symbol price updated on $price");
        }

        $this->info('Asset prices updated successfully!');
    }
}
