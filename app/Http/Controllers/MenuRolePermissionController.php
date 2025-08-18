<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\MenuRolePermission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MenuRolePermissionController extends Controller  // Make sure this extends Controller
{

    public function index(Request $request)
    {
        $roles = Role::all();
        $menus = Menu::orderBy('order')->get();
        $selectedRoleId = $request->role_id ?? $roles->first()->id;

        $permissions = MenuRolePermission::where('role_id', $selectedRoleId)
            ->get()
            ->groupBy('menu_id')
            ->map(function ($items) {
                return $items->pluck('action')->toArray();
            });

        return view('menu_role_permissions.index', compact('roles', 'menus', 'selectedRoleId', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array'
        ]);

        $roleId = $request->role_id;
        $role = Role::findOrFail($roleId);

        // Clear existing pivot
        MenuRolePermission::where('role_id', $roleId)->delete();

        foreach ($request->permissions ?? [] as $menuId => $actions) {
            $menu = Menu::find($menuId);

            foreach ($actions as $action) {
                // Save to your pivot
                MenuRolePermission::create([
                    'menu_id' => $menuId,
                    'role_id' => $roleId,
                    'action'  => $action,
                ]);

                // Generate permission name e.g. "create users"
                $permissionName = strtolower($action . ' ' . $menu->name);

                // Ensure permission exists in spatie_permissions table
                $permission = Permission::firstOrCreate(['name' => $permissionName]);

                // Assign this permission to the role
                if (!$role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permissionName);
                }
            }
        }

        return redirect()->route('menu-permission.index', ['role_id' => $roleId])
            ->with('success', 'Permissions updated successfully.');
    }

}
