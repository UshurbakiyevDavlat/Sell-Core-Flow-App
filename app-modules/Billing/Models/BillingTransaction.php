<?php

namespace AppModules\Billing\Models;

use AppModules\Billing\Concerns\Enums\BillingTransactionTypeEnum;
use AppModules\Orders\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $billing_account_id
 * @property float $amount
 * @property BillingTransactionTypeEnum $type
 * @property int $order_id
 * @property Carbon $created_at
 */
class BillingTransaction extends Model
{
    protected $table = 'billing_transactions';

    protected $fillable = [
        'billing_account_id',
        'amount',
        'type',
        'order_id',
    ];

    public function casts(): array
    {
        return [
            'type' => BillingTransactionTypeEnum::class,
            'amount' => 'float',
        ];
    }

    public function billingAccount(): BelongsTo
    {
        return $this->belongsTo(BillingAccount::class, 'billing_account_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
