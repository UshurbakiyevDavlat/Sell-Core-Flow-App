<?php

namespace AppModules\Auth\Models;

use App\Concerns\Enums\Auth\UserStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property UserStatusEnum $status
 * @property string $email
 * @method static where(string $string, string $email)
 * @method static find(int $id)
 * @method static create(array $array)
 */
class User extends Model
{
    use HasApiTokens;
    protected $fillable = [
      'name',
      'email',
      'password',
      'status',
    ];

    public function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
        ];
    }
}
