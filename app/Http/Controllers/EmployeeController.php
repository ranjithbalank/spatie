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
        $users = User::all();
        $employees = Employees::query()
            ->leftJoin('designations', 'employees_details.designation_id', '=', 'designations.id')
            ->when($search, function ($query) use ($search) {
                $query->where('employees_details.emp_name', 'like', "%{$search}%")
                    ->orWhere('employees_details.emp_id', 'like', "%{$search}%")
                    ->orWhere('employees_details.manager_id', 'like', "%{$search}%");
            })
            ->select('employees_details.*', 'designations.designation_name')
            ->orderBy('designations.designation_name', 'asc')
            ->paginate(5);

        return view("employees.index", compact("search", 'employees','users'));
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
        dd($request->all());
        // 1. Validate the incoming request data
        $validated_data = $request->validate([
            "emp_id" => "required|string|max:255|unique:employees_details,emp_id",
            "emp_name" => "required|string|max:255",

            // FIX: The validation rule for manager_id must reference the 'emp_id' column, not 'id'.
            "manager_id" => "nullable|string|exists:employees_details,emp_id",

            "unit_id" => "nullable|integer|exists:units,id",
            "department_id" => "nullable|integer|exists:departments,id",
            "designation_id" => "nullable|integer|exists:designations,id",
            "doj" => "nullable|date",
            "dor" => "nullable|date|after_or_equal:doj",
            "dob"=> "nullable|date",
            "leave_balance" => "nullable|integer|min:0", // This rule is commented out.
            "status" => "required|string|in:active,inactive",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8",
        ]);

        try {
            // 2. Create the User record first
            $user = User::create([
                'name' => $validated_data['emp_name'],
                'email' => $validated_data['email'],
                'password' => Hash::make($validated_data['password']),
                'leave_balance' => $validated_data['leave_balance'] ?? 0, // Default to 0 if not provided
            ]);

            // 3. Add the new user's ID to the validated data for the employee
            $validated_data["user_id"] = $user->id;

            // 4. Add created_by and updated_by fields
            $validated_data['created_by'] = Auth::id();
            $validated_data['updated_by'] = Auth::id();

            // 5. Create the Employee record, linked to the new user
            // Ensure your Employees model is configured with a fillable array
            $employee = Employees::create($validated_data);

            return redirect()->route('employees.index')
                ->with('success', 'Employee and associated user created successfully.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
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
        $user = $employee->user;
        // Fetch all the data needed for the form dropdowns
        $units = Unit::all();
        $employees = Employees::all(); // This is for the manager dropdown
        $departments = Department::all();
        $designations = Designation::all();

        // Pass all the data to the view
        return view('employees.create', compact('employee', 'units', 'employees', 'departments', 'designations', 'user'));
    }
    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $employee = Employees::findOrFail($id);
        $user = $employee->user;

        // Validate everything except emp_id
        $validated_data = $request->validate([
            "emp_name" => "required|string|max:255",
            "manager_id" => "nullable|integer|exists:employees_details,emp_id",
            "unit_id" => "nullable|integer|exists:units,id",
            "department_id" => "nullable|integer|exists:departments,id",
            "designation_id" => "nullable|integer|exists:designations,id",
            "doj" => "nullable|date",
            "dor" => "nullable|date|after_or_equal:doj",
            "leave_balance" => "nullable|string|min:0",
            "status" => "required|string|in:active,inactive",
            "email" => [
                "required",
                "email",
                Rule::unique('users')->ignore($user->id), // keep email unique
            ],
            "password" => "nullable|string|min:8|confirmed",
        ]);
        // @dd($user->leave_balance);
        try {
            // Update User
            $user->name = $validated_data['emp_name'];
            $user->email = $validated_data['email'];
            $user->leave_balance = $validated_data['leave_balance'];
            $user->status = $validated_data['status'];
            if ($request->filled('password')) {
                $user->password = Hash::make($validated_data['password']);
            }
            $user->save();

            // Update Employee (exclude emp_id)
            $employee->fill(collect($validated_data)->except(['email', 'password',"leave_balance"])->toArray());
            $employee->updated_by = Auth::id();
            $employee->save();

            return redirect()->route('employees.index')
                ->with('success', 'Employee details updated successfully.');

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
