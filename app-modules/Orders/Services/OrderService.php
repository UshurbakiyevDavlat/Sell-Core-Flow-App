<?php

namespace AppModules\Orders\Services;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Events\OrderCanceledEvent;
use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Orders\Repositories\OrderRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka;
use RuntimeException;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private AssetRepository $assetRepository,
    )
    {
    }

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
                'status' => $data['status'],
            ]);

            if ($order->type == OrderTypeEnum::Limit) {
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

            return $order;
        });
    }

    public function cancelOrder(int $orderId, int $userId): bool
    {
        $order = $this->repository->getById($orderId);

        if (!$order || $order->userId !== $userId) {
            return false;
        }

        $this->repository->update($orderId, ['status' => OrderStatusEnum::Cancelled->value]);

        event(new OrderCanceledEvent($order));

        return true;
    }

    public function executeMarketOrder(int $orderId): bool
    {
        $order = $this->repository->getById($orderId);
        if (
            !$order
            || $order->type != OrderTypeEnum::Market
            || $order->status != OrderStatusEnum::Pending
        ) {
            throw new RuntimeException('Fit order not found'); //todo return null, because this is controller's responsibility
        }

        // Получаем текущую цену актива
        $asset = $this->assetRepository->getById($order->assetId); //todo make bridge and use it instead direct call
        if (!$asset) {
            throw new RuntimeException('Asset not found'); //todo return null, because this is controller's responsibility
        }

        // Исполняем ордер по текущей цене
        $updatedOrder = $this->repository->update($orderId, [
            'price' => $asset->price,
            'status' => OrderStatusEnum::Executed->value,
        ]);

        if (!$updatedOrder) {
            throw new RuntimeException('Failed to update order'); //todo return null, because this is controller's responsibility
        }

        event(new OrderExecutedEvent($updatedOrder));

        return true;
    }
}
