<?php

namespace AppModules\Trades\Http\Controllers;

use AppModules\Trades\Http\Requests\CreateTradesRequest;
use AppModules\Trades\Http\Resources\TradesResource;
use AppModules\Trades\Services\TradesService;
use Illuminate\Http\Response;

readonly class TradesController
{
    public function __construct(private TradesService $service)
    {
    }

    public function store(CreateTradesRequest $request): TradesResource
    {
        $trade = $this->service->createTrade($request->validated());

        return new TradesResource($trade);
    }

    public function execute(int $id): Response
    {
        $this->service->executeTrade($id);

        return response()->noContent();
    }
}
