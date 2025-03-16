<?php

namespace AppModules\Orders\Consumers;

use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Orders\Repositories\OrderRepository;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;

class ExecuteLimitOrdersByPriceUpdate extends Command
{
    protected $signature = 'kafka:consume:limit_orders_by_price_update';

    /**
     * @throws Exception
     * @throws ConsumerException
     */
    public function handle(OrderRepository $orderRepository): void
    {
        Kafka::consumer(['asset_price_update'])
            ->withHandler(function (ConsumedMessage $message) use ($orderRepository) {
                $messageBody = $message->getBody();
                $assetId = $messageBody['asset_id'] ?? null;
                $newPrice = $messageBody['price'] ?? null;

                if (! $assetId || ! $newPrice) {
                    throw new \Exception('AssetId or newPrice missing');
                }

                $pendingOrders = $orderRepository->getPendingLimitOrdersByAsset($assetId);
                $pendingOrdersIds = array_map(fn ($order) => $order->id, $pendingOrders);

                if (! empty($pendingOrdersIds)) {
                    $orderRepository->bulkUpdateStatus($pendingOrdersIds, OrderStatusEnum::Executed->value);

                    foreach ($pendingOrders as $pendingOrder) {
                        event(new OrderExecutedEvent($pendingOrder));
                    }
                }
            })
            ->build()
            ->consume();
    }
}
