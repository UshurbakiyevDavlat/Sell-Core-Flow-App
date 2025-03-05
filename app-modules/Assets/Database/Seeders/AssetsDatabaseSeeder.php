<?php

namespace AppModules\Assets\Database\Seeders;

use Illuminate\Database\Seeder;

class AssetsDatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(AssetsSeeder::class);
    }
}
