<?php

namespace AppModules\Assets\Database\Factories;

use AppModules\Assets\Concerns\Enums\AssetTypeEnum;
use AppModules\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'symbol' => $this->faker->word(),
            'name' => $this->faker->name(),
            'type' => AssetTypeEnum::cases()[array_rand([AssetTypeEnum::cases()])],
            'price' => $this->faker->randomFloat(2, 0, 999999),
        ];
    }
}
