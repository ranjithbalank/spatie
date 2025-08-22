<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Employees;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $employees = Employees::query()
            ->leftJoin('designations', 'employees_details.designation_id', '=', 'designations.id')
            ->when($search, function ($query) use ($search) {
                $query->where('employees_details.employee_name', 'like', "%{$search}%")
                    ->orWhere('employees_details.emp_id', 'like', "%{$search}%")
                    ->orWhere('employees_details.manager_id', 'like', "%{$search}%");
            })
            ->select('employees_details.*', 'designations.designation_name')
            ->orderBy('designations.designation_name', 'asc')
            ->paginate(5);

        return view("employees.index", compact("search", 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all();
        $users = User::all();
        $employees = Employees::all();
        $departments = Department::all();
        $designations = Designation::all();
        return view("employees.create",compact('units', 'users', 'employees', 'departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
    {
        // 1. Validate the incoming request data with the correct table name
        $validated_data = $request->validate([
            "emp_id" => "required|string|max:255|unique:employees_details,emp_id",
            "employee_name" => "required|string|max:255",
            "manager_id" => "nullable|integer|exists:employees_details,id",
            "unit_id" => "nullable|integer|exists:units,id",
            "department_id" => "nullable|integer|exists:departments,id",
            "designation_id" => "nullable|integer|exists:designations,id",
            "doj" => "nullable|date",
            "dor" => "nullable|date|after_or_equal:doj",
            "leave_balance" => "nullable|integer|min:0",
            "status" => "required|string|in:active,inactive",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8",
        ]);

        try {
            // 2. Create the User record first
            $user = User::create([
                'name' => $validated_data['employee_name'],
                'email' => $validated_data['email'],
                'password' => Hash::make($validated_data['password']),
            ]);

            // 3. Add the new user's ID to the validated data for the employee
            $validated_data["user_id"] = $user->id;

            // 4. Add created_by and updated_by fields
            $validated_data['created_by'] = Auth::id();
            $validated_data['updated_by'] = Auth::id();

            // 5. Create the Employee record, linked to the new user
            $employee = Employees::create($validated_data);

            return redirect()->route('employees.index')
                ->with('success', 'Employee and associated user created successfully.');

        } catch (ValidationException $e) {
            // If validation fails, return with errors
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Handle other general exceptions
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating employee: ' . $e->getMessage());
        }
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
         $employee = Employees::findOrFail($id);

        // Fetch all the data needed for the form dropdowns
        $units = Unit::all();
        $employees = Employees::all(); // This is for the manager dropdown
        $departments = Department::all();
        $designations = Designation::all();

        // Pass all the data to the view
        return view('employees.create', compact('employee', 'units', 'employees', 'departments', 'designations'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employees::findOrFail($id);

        // Get the user record linked to this employee
        $user = $employee->user;

        // Validation rules for updating
        $validated_data = $request->validate([
            "emp_id" => ["required", "string", "max:255", Rule::unique('employees_details')->ignore($employee->id)],
            "employee_name" => "required|string|max:255",
            "manager_id" => "nullable|integer|exists:employees_details,id",
            "unit_id" => "nullable|integer|exists:units,id",
            "department_id" => "nullable|integer|exists:departments,id",
            "designation_id" => "nullable|integer|exists:designations,id",
            "doj" => "nullable|date",
            "dor" => "nullable|date|after_or_equal:doj",
            "leave_balance" => "nullable|integer|min:0",
            "status" => "required|string|in:active,inactive",
            "email" => ["required", "email", Rule::unique('users')->ignore($user->id)],
            "password" => "nullable|string|min:8|confirmed", // Password is now optional
        ]);

        try {
            // Update the User record
            $user->name = $validated_data['employee_name'];
            $user->email = $validated_data['email'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated_data['password']);
            }
            $user->save();

            // Update the Employee record
            $employee->fill($validated_data);
            $employee->updated_by = Auth::id();
            $employee->save();

            return redirect()->route('employees.index')
                ->with('success', 'Employee details updated successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        // 1. Find the employee by their ID
        $employee = Employees::findOrFail($id);

        // 2. Get the associated user record
        $user = $employee->user;

        // 3. Delete the employee record first
        $employee->delete();

        // 4. If a user exists, delete the user record
        if ($user) {
            $user->delete();
        }

        // 5. Redirect back with a success message
        return redirect()->route('employees.index')
                         ->with('success', 'Employee and associated user deleted successfully.');
    }

}
