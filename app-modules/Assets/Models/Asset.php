<?php

namespace AppModules\Assets\Models;

use AppModules\Assets\Concerns\Enums\AssetTypeEnum;
use AppModules\Assets\Database\Factories\AssetFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static insert(array[] $array)
 * @method static find(int $id)
 * @property int $id
 * @property string $symbol
 * @property string $name
 * @property AssetTypeEnum $type
 * @property float $price
 * @property Carbon $created_at
 */
class Asset extends Model
{
    use HasFactory;

    protected $fillable = ['symbol', 'name', 'type', 'price'];

    protected function casts(): array
    {
        return [
            'type' => AssetTypeEnum::class,
        ];
    }

    protected static function newFactory(): AssetFactory
    {
        return AssetFactory::new();
    }
}
