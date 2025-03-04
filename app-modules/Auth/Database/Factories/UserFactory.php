<?php

namespace AppModules\Auth\Database\Factories;

use App\Concerns\Enums\Auth\UserStatusEnum;
use AppModules\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $this->faker->password(),
            'status' => UserStatusEnum::cases()[array_rand(UserStatusEnum::cases())],
        ];
    }
}
