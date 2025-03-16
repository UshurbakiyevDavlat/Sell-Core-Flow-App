<?php

namespace AppModules\Billing\Repositories;

use AppModules\Billing\Concerns\Enums\BillingTransactionTypeEnum;
use AppModules\Billing\DTO\BalanceDTO;
use AppModules\Billing\DTO\TransactionsDTO;
use AppModules\Billing\Models\BillingAccount;
use AppModules\Billing\Models\BillingTransaction;
use Illuminate\Support\Facades\Cache;

class BillingRepository
{
    public function getUserBalance(int $userId): ?BalanceDTO
    {
        return Cache::remember("billing_balance_$userId", 60, function () use ($userId) {
            $account = BillingAccount::query()->where('user_id', $userId)->first();

            return BalanceDTO::fromModel($account);
        });
    }

    public function getTransactions(int $billingAccountId): array
    {
        return BillingTransaction::query()
            ->where('billing_account_id', $billingAccountId)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($tx) => TransactionsDTO::fromModel($tx))
            ->toArray();
    }

    public function updateBalance(int $billingAccountId, float $amount): void
    {
        BillingAccount::query()
            ->where('id', $billingAccountId)
            ->increment('balance', $amount);

        Cache::forget("billing_balance_$billingAccountId");
    }

    public function createUserBalance(int $userId): BalanceDTO
    {
        $billingAccount = BillingAccount::query()->create([
            'user_id' => $userId,
            'balance' => 0, // Начальный баланс 0
        ]);

        Cache::forget("billing_balance_$userId");

        return BalanceDTO::fromModel($billingAccount);
    }

    public function createTransaction(
        int $billingAccountId,
        float $amount,
        BillingTransactionTypeEnum $type,
        ?int $orderId = null
    ): void {
        BillingTransaction::query()->create([
            'billing_account_id' => $billingAccountId,
            'amount' => $amount,
            'type' => $type->value,
            'order_id' => $orderId,
        ]);
    }
}
