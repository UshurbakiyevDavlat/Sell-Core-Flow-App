<?php

namespace AppModules\Trades\Models;

use AppModules\Auth\Models\User;
use AppModules\Orders\Models\Order;
use AppModules\Trades\Concerns\Enums\TradeStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property float $price
 * @property int quantity
 * @property TradeStatusEnum $status
 * @property Carbon $created_at
 */
class Trade extends Model
{
    protected $table = 'trades';
    protected $fillable = [
        'order_id',
        'user_id',
        'price',
        'quantity',
        'status',
    ];

    public function casts(): array
    {
        return [
            'status' => TradeStatusEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
