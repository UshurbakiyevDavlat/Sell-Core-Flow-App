<?php

namespace AppModules\Billing\Services;

use AppModules\Billing\Concerns\Enums\BillingTransactionTypeEnum;
use AppModules\Billing\DTO\BalanceDTO;
use AppModules\Billing\Repositories\BillingRepository;
use AppModules\Orders\Repositories\OrderRepository;
use AppModules\Trades\Repositories\TradesRepository;

readonly class BillingService
{
    //todo need bridges for other modules functional
    public function __construct(
        protected BillingRepository $billingRepository,
        protected OrderRepository   $orderRepository,
        protected TradesRepository  $tradeRepository
    )
    {
    }

    public function charge(int $userId, int $orderId): bool
    {
        $order = $this->orderRepository->getById($orderId);
        if (!$order || $order->userId !== $userId) {
            return false;
        }

        $amount = $order->price * $order->quantity; // Вычисляем сумму списания

        $balance = $this->billingRepository->getUserBalance($userId);
        if ($balance && $balance->balance >= $amount) {
            $this->billingRepository->updateBalance($balance->id, -$amount);
            $this->billingRepository->createTransaction(
                $balance->id,
                -$amount,
                BillingTransactionTypeEnum::Debit,
                $orderId
            );
            return true;
        }
        return false;
    }

    public function refill(int $userId, $amount): void
    {
        $balance = $this->billingRepository->getUserBalance($userId);

        if ($balance) {
            $this->billingRepository->updateBalance($balance->id, $amount);
            $this->billingRepository->createTransaction(
                $balance->id,
                $amount,
                BillingTransactionTypeEnum::Credit,
            );
        }
    }

    public function release(int $userId, int $orderId): void
    {
        $order = $this->orderRepository->getById($orderId);
        if (!$order || $order->userId !== $userId) {
            return;
        }

        $amount = $order->price * $order->quantity; // Вычисляем сумму возврата

        $balance = $this->billingRepository->getUserBalance($userId);
        if ($balance) {
            $this->billingRepository->updateBalance($balance->id, $amount);
            $this->billingRepository->createTransaction(
                $balance->id,
                $amount,
                BillingTransactionTypeEnum::Credit,
                $orderId
            );
        }
    }

    public function profit(int $userId, int $tradeId): void
    {
        $trade = $this->tradeRepository->getById($tradeId);
        if (!$trade || $trade->userId !== $userId) {
            return;
        }

        $amount = $trade->price * $trade->quantity; // Вычисляем прибыль

        $balance = $this->billingRepository->getUserBalance($userId);
        if ($balance) {
            $this->billingRepository->updateBalance($balance->id, $amount);
            $this->billingRepository->createTransaction(
                $balance->id,
                $amount,
                BillingTransactionTypeEnum::Credit,
                $tradeId
            );
        }
    }

    public function getBalance(int $userId): ?BalanceDTO
    {
        return $this->billingRepository->getUserBalance($userId);
    }

    public function getTransactions(int $balanceId): array
    {
        return $this->billingRepository->getTransactions($balanceId);
    }
}
