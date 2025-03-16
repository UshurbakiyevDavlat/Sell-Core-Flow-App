<?php

namespace AppModules\Strategies\Services;

use AppModules\Strategies\Models\StrategyRun;
use AppModules\Strategies\Repositories\StrategyRunRepository;

class StrategyRunService
{
    public function __construct(
        protected StrategyRunRepository $strategyRunRepository
    ) {}

    /**
     * Логирование запуска стратегии
     */
    public function logRun(array $data): StrategyRun
    {
        return $this->strategyRunRepository->logRun($data);
    }

    /**
     * Обновление статуса выполнения стратегии
     */
    public function updateStatus(int $runId, string $status, ?string $message = null): bool
    {
        return $this->strategyRunRepository->updateStatus($runId, $status, $message);
    }

    /**
     * Получение истории запусков стратегий пользователя
     */
    public function getUserRuns(int $userId, int $limit = 10)
    {
        return $this->strategyRunRepository->getUserRuns($userId, $limit);
    }
}
