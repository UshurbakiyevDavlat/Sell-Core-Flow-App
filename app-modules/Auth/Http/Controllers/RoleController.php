<?php

namespace AppModules\Auth\Http\Controllers;

use AppModules\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

// todo make refactor
class RoleController
{
    public function index(): JsonResponse
    {
        return response()->json(Role::with('permissions')->get());
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string|unique:roles']);
        Role::create(['name' => $request->name]);

        return response()->json(['message' => 'Role created successfully']);
    }

    public function delete(Role $role): JsonResponse
    {
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignRole(User $user, Role $role): JsonResponse
    {
        $user->assignRole($role);

        return response()->json(['message' => "Role '{$role->name}' assigned to user {$user->id}"]);
    }

    public function assignPermissions(Request $request, Role $role): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => "Permissions assigned to role '{$role->name}'",
            'permissions' => $role->permissions,
        ]);
    }
}
