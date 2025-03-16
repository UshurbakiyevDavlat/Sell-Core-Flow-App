<?php

namespace AppModules\Billing\Models;

use AppModules\Auth\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property float $balance
 */
class BillingAccount extends Model
{
    protected $table = 'billing_accounts';

    protected $fillable = [
        'user_id',
        'balance',
    ];

    public function casts(): array
    {
        return [
            'balance' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
