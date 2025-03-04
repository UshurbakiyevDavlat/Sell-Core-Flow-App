<?php

namespace AppModules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;

class AuthDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);
    }
}
