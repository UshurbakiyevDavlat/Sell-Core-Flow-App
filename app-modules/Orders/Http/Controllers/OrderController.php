<?php

namespace AppModules\Orders\Http\Controllers;

use AppModules\Orders\Http\Requests\CreateOrderRequest;
use AppModules\Orders\Http\Resources\OrderResource;
use AppModules\Orders\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

readonly class OrderController
{
    public function __construct(private OrderService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->getAll($request->query('per_page', 10)));
    }

    /**
     * @throws Exception
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        return response()->json(new OrderResource($this->service->create($request->validated())), 201);
    }

    public function destroy(int $id): Response
    {
        $this->service->delete($id);
        return response()->noContent();
    }

    public function executeMarketOrder(int $id): Response
    {
        $this->service->executeMarketOrder($id);
        return response()->noContent();
    }

}
