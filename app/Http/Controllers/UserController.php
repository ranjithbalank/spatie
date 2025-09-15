<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employees;
use Illuminate\Http\Request;
use App\Imports\EmployeesImport;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterRoleId = $request->input('role_id');
        $employee = Employees::all();
        $users = User::with('roles') // Eager load the roles relationship
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->when($filterRoleId, function ($query, $filterRoleId) {
                // Filter by role ID using the whereHas method on the 'roles' relationship
                $query->whereHas('roles', function ($q) use ($filterRoleId) {
                    $q->where('id', $filterRoleId);
                });
            })
            ->orderBy('name')
            ->paginate(10);

        // Fetch all roles to populate the filter dropdown in your view
        $roles = Role::orderBy('name')->get();

        return view("users.index", compact("users", "search", "roles", "filterRoleId", "employee"));
    }


    /**
     * Show the form for creating a new resource.
     */
    // In your UserController.php
    public function create()
    {
        $roles = Role::all(); // Or whatever method you use to get roles
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        // Create the user
        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role_id = $validated['role'];
        $user->save();
        $role = Role::findById($validated['role']);
        $user->assignRole($role->name);
        Employees::updateOrCreate(
            ['emp_name' => $validated['name']]
        );
        // Redirect to the users list with a success message
        return redirect()->route('users.index')->with('success', 'User created successfully!');
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
        // Find the user by their ID. findOrFail will throw a 404 error
        // if a user with that ID is not found.
        $user = User::findOrFail($id);

        // Fetch all roles from the database to populate the dropdown.
        $roles = Role::all();

        // Return the view and pass both the user object and the roles
        // to it.
        return view('users.create', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the user first.
        $user = User::findOrFail($id);

        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'status' => 'required|string|in:active,inactive',
        ]);
        $role = Role::findById($validated['role']);
        // Update the user's attributes
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role'], // This line updates the role
            'status' => $validated['status'],
        ]);
        $user->syncRoles($role->name);
        Employees::updateOrCreate(
            ['user_id' => $user->id],
            ['emp_name' => $validated['name']]
        );
        // Redirect to the users list with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete dependent details
        Employees::where('emp_id', $id)->delete();

        // delete employee
        User::where('id', $id)->delete();

        return back()->with('success', 'Employee deleted successfully.');
    }

    public function import_csv()
    {
        return view('users.import');
    }

    public function import(Request $request)
    {
        // @dd($request->all());
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
            return back()->with('success', 'Employees imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error during import: ' . $e->getMessage());
        }
    }
}
