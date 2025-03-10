<?php

namespace AppModules\Orders\Consumers;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Events\OrderExecutedEvent;
use AppModules\Orders\Repositories\OrderRepository;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;
use RuntimeException;

class ExecuteLimitPendingOrder extends Command
{
    protected $signature = 'kafka:consume:limit_pending_orders';

    /**
     * @throws Exception
     * @throws ConsumerException
     */
    public function handle(
        OrderRepository $orderRepository,
        AssetRepository $assetRepository,
    ): void
    {
        Kafka::consumer(['limit_pending_orders']) //todo rename it to limit_pending_orders
            ->withHandler(function (ConsumedMessage $message) use ($orderRepository, $assetRepository) {
                $messageBody = $message->getBody();
                $orderId = $messageBody['order_id'] ?? null;
                if (!$orderId) {
                    throw new ConsumerException('OrderId missing');
                }

                $order = $orderRepository->getById($orderId);
                if (!$order) {
                    throw new RuntimeException('Order not found');
                }

                $asset = $assetRepository->getById($order->assetId); //todo make bridge and use it instead direct call
                if (!$asset) {
                    throw new RuntimeException('Asset not found');
                }

            if ($asset->price == $order->price) {
                $orderRepository->update($orderId, ['status' => OrderStatusEnum::Executed->value]);
                event(new OrderExecutedEvent($order));
            }
            })
            ->build()
            ->consume();
    }
}
