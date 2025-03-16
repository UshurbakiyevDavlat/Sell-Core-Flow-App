<?php

namespace AppModules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController
{
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function create(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions']);
        Permission::create(['name' => $request->name]);

        return response()->json(['message' => 'Permission created successfully']);
    }

    public function delete(Permission $permission)
    {
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
