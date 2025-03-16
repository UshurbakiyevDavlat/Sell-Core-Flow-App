<?php

namespace AppModules\Strategies\Console\Commands;

use AppModules\Strategies\Concerns\StrategyTypesEnum;
use AppModules\Strategies\Services\StrategyService;
use Illuminate\Console\Command;

class TestStrategyCommand extends Command
{
    protected $signature = 'strategy:test {strategy} {user_id} {asset_id} {quantity}';

    protected $description = 'Запуск тестовой стратегии';

    public function __construct(
        protected StrategyService $strategyService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $strategyName = StrategyTypesEnum::from($this->argument('strategy'));
        $userId = (int) $this->argument('user_id');
        $assetId = (int) $this->argument('asset_id');
        $quantity = (float) $this->argument('quantity');

        $this->info("▶ Запуск стратегии: $strategyName->value для пользователя #$userId с активом #$assetId (Кол-во: $quantity)");

        try {
            $order = $this->strategyService->runStrategy($strategyName, $userId, $assetId, $quantity);

            if ($order) {
                $this->info("✅ Ордер успешно создан: ID #$order->id, Цена: $order->price, Кол-во: $order->quantity");
            } else {
                $this->warn('❌ Ордер не был создан. Проверьте логи.');
            }
        } catch (\Exception $e) {
            $this->error('Ошибка выполнения теста стратегии: '.$e->getTraceAsString());
        }
    }
}
