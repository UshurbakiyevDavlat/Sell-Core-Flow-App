<?php

namespace AppModules\Billing\DTO;

use AppModules\Billing\Models\BillingAccount;

readonly class BalanceDTO
{
    public function __construct(
        public int   $id,
        public int   $userId,
        public float $balance,
    )
    {
    }

    public static function fromModel(?BillingAccount $billingAccount): ?self
    {
        if (!$billingAccount) {
            return null;
        }

        return new self(
            id: $billingAccount->id,
            userId: $billingAccount->user_id,
            balance: $billingAccount->balance
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'balance' => $this->balance,
        ];
    }
}

