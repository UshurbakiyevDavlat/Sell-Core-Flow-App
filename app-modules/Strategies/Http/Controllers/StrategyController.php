<?php

namespace AppModules\Strategies\Http\Controllers;

use AppModules\Strategies\Http\Requests\RunStrategyRequest;
use AppModules\Strategies\Services\StrategyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class StrategyController extends Controller
{
    public function __construct(
        protected StrategyService $strategyService
    ) {}

    /**
     * Запуск стратегий для пользователя
     */
    public function runStrategies(RunStrategyRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->strategyService->runUserStrategies(
            $data['user_id'],
            $data['strategies'],
            $data['asset_ids'],
            $data['quantity'],
        );

        return response()->json(['message' => 'Стратегии успешно запущены']);
    }
}
