<?php

namespace AppModules\Billing\DTO;

use AppModules\Billing\Concerns\Enums\BillingTransactionTypeEnum;
use AppModules\Billing\Models\BillingTransaction;

readonly class TransactionsDTO
{
    public function __construct(
        public int $id,
        public int $billingAccountId,
        public float $amount,
        public BillingTransactionTypeEnum $type,
        public ?int $orderId,
        public string $createdAt,
    ) {}

    public static function fromModel(?BillingTransaction $transaction): ?self
    {
        if (!$transaction) {
            return null;
        }

        return new self(
            id: $transaction->id,
            billingAccountId: $transaction->billing_account_id,
            amount: $transaction->amount,
            type: $transaction->type,
            orderId: $transaction->order_id,
            createdAt: $transaction->created_at->toDateTimeString(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'billing_account_id' => $this->billingAccountId,
            'amount' => $this->amount,
            'type' => $this->type->value,
            'order_id' => $this->orderId,
            'created_at' => $this->createdAt,
        ];
    }
}

