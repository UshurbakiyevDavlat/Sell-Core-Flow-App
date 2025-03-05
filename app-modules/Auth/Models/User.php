<?php

namespace AppModules\Auth\Models;

use AppModules\Auth\Concerns\Enums\Auth\UserStatusEnum;
use AppModules\Auth\Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $password
 * @property \AppModules\Auth\Concerns\Enums\Auth\UserStatusEnum $status
 * @property string $email
 * @method static where(string $string, string $email)
 * @method static find(int $id)
 * @method static create(array $array)
 */
class User extends Authenticatable implements AuthorizableContract
{
    use Authorizable;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;

    protected string $guard_name = 'api';

    protected $fillable = [
      'name',
      'email',
      'password',
      'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
