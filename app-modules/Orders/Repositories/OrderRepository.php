<?php

namespace AppModules\Orders\Repositories;

use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use AppModules\Orders\DTO\OrderDTO;
use AppModules\Orders\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrderRepository
{
    public function getAll(int $perPage = 10): LengthAwarePaginator //todo repository should return primitive or dto
    {
        return Cache::remember("orders_list_page_$perPage", 60, function () use ($perPage) {
            return Order::query()->paginate($perPage)
                ->through(fn(Order $order) => OrderDTO::fromModel($order));
        });
    }

    public function getById(int $id): ?OrderDTO
    {
        return Cache::remember("order_$id", 60, function () use ($id) {
            $order = Order::query()->find($id);
            return $order ? OrderDTO::fromModel($order) : null;
        });
    }

    public function create(array $data): OrderDTO
    {
        $order = Order::query()->create($data);
        Cache::forget("orders_list_page_10");

        return OrderDTO::fromModel($order);
    }

    public function update(int $id, array $data): ?OrderDTO
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return null;
        }

        $order->update($data);
        Cache::forget("order_$id");
        Cache::forget("orders_list_page_10");

        return OrderDTO::fromModel($order);
    }

    public function delete(int $id): bool
    {
        $order = Order::query()->find($id);
        if (!$order) {
            return false;
        }

        $order->delete();
        Cache::forget("order_$id");
        Cache::forget("orders_list_page_10");//todo сделать без хардкода

        return true;
    }

    public function getPendingLimitOrdersByAsset(int $assetId): array
    {
        return Order::query()->where('status', OrderStatusEnum::Pending->value)
            ->where('type', OrderTypeEnum::Limit->value)
            ->where('asset_id', $assetId)
            ->get()
            ->map(fn(?Order $order) => OrderDTO::fromModel($order))
            ->toArray();
    }

    public function bulkUpdateStatus(array $orderIds, string $status): void
    {
        Order::query()->whereIn('id', $orderIds)->update(['status' => $status]);
    }

}
