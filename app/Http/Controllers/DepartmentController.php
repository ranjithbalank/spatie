<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $departments = Department::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        })
            ->orderBy('name', 'asc')
            ->paginate(5);

        return view('departments.index', compact('departments', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create departments')) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'You do not have permission');
        }
        $units = Unit::all(); // fetch all units
        return view('departments.create', compact('units'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:departments,code',
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'status' => 'required|in:active,inactive',
        ]);

        $department = Department::create([
            'code' => $request->code,
            'name' => $request->name,
            'unit_id' => $request->unit_id,
            'status' => $request->status,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
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
        if (!auth()->user()->can('edit departments')) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'You do not have permission');
        }
        // Find the department or fail
        $department = Department::findOrFail($id);

        // Get all units for the dropdown
        $units = Unit::all();

        // Return the same form view as create, passing department and units
        return view('departments.create', compact('department', 'units'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Find the department
        $department = Department::findOrFail($id);

        // Update with validated data
        $department->update($validated);

        // Redirect back with success message
        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete departments')) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'You do not have permission');
        }
        // Find the department
        $department = Department::findOrFail($id);

        // Delete it
        $department->delete();

        // Redirect back with success message
        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
