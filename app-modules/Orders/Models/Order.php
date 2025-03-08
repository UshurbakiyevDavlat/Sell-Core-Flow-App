<?php

namespace AppModules\Orders\Models;

use AppModules\Assets\Models\Asset;
use AppModules\Auth\Models\User;
use AppModules\Orders\Concerns\OrderSideEnum;
use AppModules\Orders\Concerns\OrderStatusEnum;
use AppModules\Orders\Concerns\OrderTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $asset_id
 * @property OrderTypeEnum $type
 * @property OrderSideEnum $side
 * @property float $price
 * @property float $quantity
 * @property OrderStatusEnum $status
 * @property Carbon $created_at
 */
class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'asset_id',
        'type',
        'side',
        'price',
        'quantity',
        'status',
    ];

    public function casts(): array
    {
        return [
            'type' => OrderTypeEnum::class,
            'side' => OrderSideEnum::class,
            'status' => OrderStatusEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
