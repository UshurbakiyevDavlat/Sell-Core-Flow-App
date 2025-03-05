<?php

namespace AppModules\Assets\Database\Seeders;

use AppModules\Assets\Concerns\Enums\AssetTypeEnum;
use AppModules\Assets\Models\Asset;
use Illuminate\Database\Seeder;

class AssetsSeeder extends Seeder
{
    public function run(): void
    {
        Asset::factory(3)->create();
    }
}
