<?php

namespace AppModules\Assets\Console\Commands;

use AppModules\Assets\Services\AssetService;
use Exception;
use Illuminate\Console\Command;

class InitializeAssetCommand extends Command
{
    protected $signature = 'assets:init';

    public function __construct(
        private readonly AssetService $assetService,
    ) {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->assetService->initializeAssets(); // todo перенести в джобу, т.к если будет много ассетов грохнется консоль процесс.
    }
}
