<?php

namespace AppModules\Orders\Services;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Repositories\OrderRepository;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Junges\Kafka\Facades\Kafka;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private AssetRepository $assetRepository,
    )
    {
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
                'user_id' => auth()->id(),
                'asset_id' => $data['asset_id'],
                'type' => $data['type'],
                'side' => $data['side'],
                'price' => $data['price'] ?? null,
                'quantity' => $data['quantity'],
                'status' => 'pending',
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

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function executeMarketOrder(int $orderId): bool
    {
        $order = $this->repository->getById($orderId);
        if (
            !$order
            || $order->type != OrderTypeEnum::Market
            || $order->status != OrderStatusEnum::Pending
        ) {
            return false;
        }

        // Получаем текущую цену актива
        $asset = $this->assetRepository->getById($order->assetId); //todo make bridge and use it instead direct call
        if (!$asset) {
            return false;
        }

        // Исполняем ордер по текущей цене
        $this->repository->update($orderId, [
            'price' => $asset->price,
            'status' => 'executed',
        ]);

        return true;
    }
}
