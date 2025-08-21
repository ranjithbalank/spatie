<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Employees;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $validated_data = $request->validate([
            "emp_id" => "required|string|max:255|unique:employees,emp_id",
            "employee_name" => "required|string|max:255",
            "manager_id" => "nullable|integer|exists:employees,id",
            "unit_id" => "nullable|integer|exists:units,id",
            "department_id" => "nullable|integer|exists:departments,id",
            "designation_id" => "nullable|integer|exists:designations,id",
            "doj" => "nullable|date",
            "dor" => "nullable|date|after_or_equal:doj",
            "leave_balance" => "nullable|integer|min:0",
            "status" => "required|string|in:active,inactive",
        ]);

        // Add created_by and updated_by fields
        $validated_data['created_by'] = Auth::id();
        $validated_data['updated_by'] = Auth::id();

        try {
            $employee = Employees::create($validated_data);

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');

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
