<?php

namespace AppModules\Strategies\Repositories;

use AppModules\Strategies\Models\StrategyRun;

class StrategyRunRepository
{
    public function logRun(array $data): StrategyRun
    {
        return StrategyRun::query()->create($data);
    }

    public function updateStatus(
        int $runId,
        string $status,
        ?string $message = null
    ): bool {
        return StrategyRun::query()->where('id', $runId)
            ->update([
                'status' => $status,
                'message' => $message,
            ]);
    }

    public function getUserRuns(int $userId, int $limit = 10): ?array
    {
        return StrategyRun::query()->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ?->toArray();
    }
}
