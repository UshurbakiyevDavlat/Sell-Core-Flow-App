<?php

namespace AppModules\Orders\Services;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTradeModeEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Events\OrderCanceledEvent;
use AppModules\Orders\Events\OrderCreatedEvent;
use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Orders\Repositories\OrderRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka;
use RuntimeException;
use Throwable;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private AssetRepository $assetRepository,
    ) {}

    public function getById(int $id): OrderDTO
    {
        return $this->repository->getById($id);
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->repository->getAll($perPage);
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function create(array $data): OrderDTO
    {
        return DB::transaction(function () use ($data) {
            $order = $this->repository->create([
                'user_id' => $data['user_id'],
                'asset_id' => $data['asset_id'],
                'type' => $data['type'],
                'side' => $data['side'],
                'price' => $data['price'] ?? null,
                'quantity' => $data['quantity'],
                'status' => $data['status'] ?? OrderStatusEnum::Pending,
                'trade_mod' => $data['trade_mode'] ?? OrderTradeModeEnum::Paper,
            ]);

            broadcast(new OrderCreatedEvent($order));

            switch ($order->type) {
                case OrderTypeEnum::Market:
                    $this->executeMarketOrder($order);
                    break;
                case OrderTypeEnum::Limit:
                    $this->publishOrder($order);
                    break;
            }

            return $order;
        });
    }

    public function cancelOrder(int $orderId, int $userId): bool
    {
        $order = $this->repository->getById($orderId);

        if (! $order || $order->userId !== $userId) {
            return false;
        }

        $this->repository->update($orderId, ['status' => OrderStatusEnum::Cancelled->value]);

        event(new OrderCanceledEvent($order));

        return true;
    }

    private function executeMarketOrder(OrderDTO $order): void
    {
        if (
            $order->type != OrderTypeEnum::Market
            ||
            $order->status != OrderStatusEnum::Pending
        ) {
            throw new RuntimeException('Fit order not found');
        }

        if ($order->tradeMode !== OrderTradeModeEnum::Backtest) {
            // Получаем актив
            $asset = $this->assetRepository->getById($order->assetId); // todo make bridge and use it instead direct call
            if (! $asset) {
                throw new RuntimeException('Asset not found');
            }
            $assetPrice = $asset->price;
        } else {
            $assetPrice = $order->price;
        }

        // Исполняем ордер по текущей цене
        $updatedOrder = $this->repository->update(
            $order->id,
            [
                'price' => $assetPrice,
                'status' => OrderStatusEnum::Executed->value,
            ]
        );

        if (! $updatedOrder) {
            throw new RuntimeException('Failed to update order');
        }

        event(new OrderExecutedEvent($updatedOrder));
    }

    /**
     * @throws Exception
     */
    private function publishOrder(OrderDTO $order): void
    {
        Kafka::publish()
            ->onTopic('limit_pending_orders')
            ->withBody([
                'order_id' => $order->id,
                'asset_id' => $order->assetId,
                'price' => $order->price,
                'side' => $order->side,
            ])
            ->send();
    }
}
