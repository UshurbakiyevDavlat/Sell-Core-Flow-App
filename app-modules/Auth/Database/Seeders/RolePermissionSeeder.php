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
            'manage_users',
            'manage_roles',
            'view_orders',
            'create_orders',
            'update_orders',
            'delete_orders',
            'view_trades',
            'execute_trades',
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
