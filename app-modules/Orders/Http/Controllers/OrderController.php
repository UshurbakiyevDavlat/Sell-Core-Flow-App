<?php

namespace AppModules\Orders\Http\Controllers;

use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Http\Requests\CreateOrderRequest;
use AppModules\Orders\Http\Resources\OrderResource;
use AppModules\Orders\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['status'] = OrderStatusEnum::Pending;

        //todo please make resource
        return response()->json(
            new OrderResource($this->service->create($data)),
            201
        );
    }

    public function cancelOrder(int $id): Response
    {
        $userId = Auth::user()->getAuthIdentifier();

        if (!$this->service->cancelOrder($id, $userId)) {
            throw new UnprocessableEntityHttpException("Cannot cancel order");
        }

        return response()->noContent();
    }

    public function executeMarketOrder(int $id): Response
    {
        $this->service->executeMarketOrder($id);

        return response()->noContent();
    }

}
