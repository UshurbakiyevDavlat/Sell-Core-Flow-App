<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $modulesPath = base_path('app-modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        // Получаем только папки модулей
        $modules = array_map('basename', glob("$modulesPath/*", GLOB_ONLYDIR));

        foreach ($modules as $module) {
            $seederNamespace = "AppModules\\$module\\Database\\Seeders\\";
            $seederClass = $seederNamespace . "{$module}DatabaseSeeder";

            $seederPath = "$modulesPath/$module/Database/Seeders/{$module}DatabaseSeeder.php";

            if (file_exists($seederPath) && class_exists($seederClass)) {
                $this->call($seederClass);
            } else {
                $this->command->warn("Seeder не найден: $seederClass (модуль $module)");
            }
        }
    }
}
