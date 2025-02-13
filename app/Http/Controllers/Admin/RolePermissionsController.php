<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles-permissions.index', compact('roles', 'permissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if (Role::where('name', $request->name)->exists()) {
            return redirect()->route('admin.roles-permissions.index')->with('error', 'Role already exists.');
        }

        Role::create(['name' => $request->name]);

        return redirect()->route('admin.roles-permissions.index')->with('success', 'Role created successfully.');
    }

    public function deleteRole(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles-permissions.index')->with('error', 'Cannot delete admin role.');
        }

        $role->delete();
        return redirect()->route('admin.roles-permissions.index')->with('success', 'Role deleted successfully.');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if (Permission::where('name', $request->name)->exists()) {
            return redirect()->route('admin.roles-permissions.index')->with('error', 'Permission already exists.');
        }

        Permission::create(['name' => $request->name]);

        return redirect()->route('admin.roles-permissions.index')->with('success', 'Permission created successfully.');
    }

    public function deletePermission(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.roles-permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
