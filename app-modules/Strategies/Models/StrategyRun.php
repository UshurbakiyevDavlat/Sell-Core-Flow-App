<?php

namespace AppModules\Strategies\Models;

use AppModules\Assets\Models\Asset;
use AppModules\Auth\Models\User;
use AppModules\Strategies\Concerns\StrategyStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyRun extends Model
{
    protected $table = 'strategy_runs';

    protected $fillable = [
        'user_id',
        'strategy',
        'asset_id',
        'quantity',
        'status',
        'message',
    ];

    public function casts(): array
    {
        return [
            'status' => StrategyStatusEnum::class,
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
