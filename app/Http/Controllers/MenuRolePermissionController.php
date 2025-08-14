<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\MenuRolePermission;
use Spatie\Permission\Models\Role;

class MenuRolePermissionController extends Controller
{
     public function index(Request $request)
    {
        $roles = Role::all();
        $menus = Menu::orderBy('order')->get();
        $selectedRoleId = $request->role_id ?? $roles->first()->id;

        // Existing permissions for selected role
        $permissions = MenuRolePermission::where('role_id', $selectedRoleId)
            ->get()
            ->groupBy('menu_id')
            ->map(function ($items) {
                return $items->pluck('action')->toArray();
            });

        return view('menu_role_permissions.index', compact('roles', 'menus', 'selectedRoleId', 'permissions'));
    }

    public function create()
    {

    }



    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array'
        ]);

        $roleId = $request->role_id;

        // Delete old permissions for this role
        MenuRolePermission::where('role_id', $roleId)->delete();

        // Insert new permissions
        foreach ($request->permissions ?? [] as $menuId => $actions) {
            foreach ($actions as $action) {
                MenuRolePermission::create([
                    'menu_id' => $menuId,
                    'role_id' => $roleId,
                    'action' => $action,
                ]);
            }
        }

        return redirect()->route('menu-permission.index', ['role_id' => $roleId])
            ->with('success', 'Permissions updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
