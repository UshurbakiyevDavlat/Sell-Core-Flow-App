<?php

namespace AppModules\Orders\Consumers;

use AppModules\Assets\Repositories\AssetRepository;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Repositories\OrderRepository;
use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\ConsumedMessage;
use RuntimeException;

class ProcessPendingOrders extends Command
{
    protected $signature = 'kafka:consume:orders';

    /**
     * @throws Exception
     * @throws ConsumerException
     */
    public function handle(
        OrderRepository $orderRepository,
        AssetRepository $assetRepository,
    ): void
    {
        // Listen for price_update
        Kafka::consumer(['price_update'])
            ->withHandler(function (ConsumedMessage $message) use ($orderRepository) {
                $messageBody = $message->getBody();
                $assetId = $messageBody['asset_id'] ?? null;
                $newPrice = $messageBody['price'] ?? null;

                if (!$assetId || !$newPrice) {
                    throw new ConsumerException('AssetId or newPrice missing');
                }

                /**
                 * Разделение на buy и sell отдельно понадобится лишь в том случае если у нас будет
                 * - разная логика обновления для них или же будет частичное исполнение ордера
                 */
                $pendingOrdersIds = $orderRepository->getPendingLimitOrdersIdsByAsset($assetId);
                if (!empty($pendingOrdersIds)) {
                    $orderRepository->bulkUpdateStatus($pendingOrdersIds, OrderStatusEnum::Executed->value);
                }
            })
            ->build()
            ->consume();

        // Listen for pending orders
        Kafka::consumer(['pending_orders'])
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

                $orderRepository->update($orderId, ['status' => OrderStatusEnum::Executed->value]);
            })
            ->build()
            ->consume();
    }
}
