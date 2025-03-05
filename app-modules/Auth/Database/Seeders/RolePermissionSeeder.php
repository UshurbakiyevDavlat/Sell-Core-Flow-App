<?php

namespace AppModules\Auth\Database\Seeders;

use AppModules\Auth\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'api';

        // Создаём роли с guard
        $adminRole = Role::findOrCreate('admin', $guard);
        $moderatorRole = Role::findOrCreate('moderator', $guard);
        $userRole = Role::findOrCreate('user', $guard);

        // Создаём разрешения с guard
        $permissions = [
            'manage_users',    // Управление пользователями (создание, удаление, обновление)
            'manage_roles',    // Управление ролями и разрешениями
            'manage_assets',   // Управление активами (добавление, редактирование, удаление)
            'view_orders',     // Просмотр ордеров
            'create_orders',   // Создание ордеров
            'update_orders',   // Обновление ордеров
            'delete_orders',   // Удаление ордеров
            'view_trades',     // Просмотр сделок
            'execute_trades',  // Исполнение сделок
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, $guard);
        }

        // Назначаем права ролям (только для guard 'api')
        $adminRole->syncPermissions(Permission::query()->where('guard_name', $guard)->get());
        $moderatorRole->syncPermissions(
            Permission::query()->where('guard_name', $guard)
                ->whereIn('name', ['view_orders', 'create_orders', 'update_orders', 'view_trades'])
                ->get()
        );
        $userRole->syncPermissions(
            Permission::query()->where('guard_name', $guard)
                ->whereIn('name', ['view_orders', 'create_orders'])
                ->get()
        );

        if (!User::where('email', 'admin@example.com')->exists()) {
            /** @var User $adminUser */
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);

            $adminUser->assignRole($adminRole);
        }
    }
}
