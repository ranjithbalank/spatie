<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Container\Attributes\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::with('permissions')
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('roles.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create roles')) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'You do not have permission');
        }
        $permissions = Permission::all();
        $menu = [
            'users' => ['label' => 'Users', 'actions' => ['create', 'edit', 'delete', 'view', 'approve']],
            'assets' => ['label' => 'Assets', 'actions' => ['create', 'edit', 'delete', 'view', 'approve']],
            'leaves' => ['label' => 'Leaves', 'actions' => ['create', 'edit', 'delete', 'view', 'approve']],
        ];
        return view('roles.create', compact('permissions', 'menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        // Sync permissions if any
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions'));
        }
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
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
        if (!auth()->user()->can('create roles')) {
            return redirect()
                ->route('permissions.index')
                ->with('error', 'You do not have permission');
        }
        return view('roles.create', [
            'role' => Role::findOrFail($id),
            "permissions" => Permission::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);
        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();

        // Sync permissions
        $role->syncPermissions($request->input('permissions', [])); // empty array if none checked

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the role you want to delete
        $role = Role::findOrFail($id);

        // This is the crucial fix: Find all users with this role and set their role_id to null
        User::where('role_id', $role->id)->update(['role_id' => null]);

        // Now, safely detach users from the Spatie's intermediate table.
        $role->users()->detach();

        // Now you can safely delete the role itself.
        $role->delete();

        // Redirect with a success message
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
