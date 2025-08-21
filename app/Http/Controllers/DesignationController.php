<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

use function Laravel\Prompts\search;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $designations = Designation::when($search, function ($query, $search) {
                   $query->where('designation_name', 'like', "%{$search}%")
              ->orWhere('designation_code', 'like', "%{$search}%")
              ->orWhere('status','like',"%{$search}%");
        })
        ->orderBy('designation_name', 'asc')
        ->paginate(5);
        return view ('designations.index',compact("designations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('designations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
         $request->validate([
            'code' => 'required|string|max:50|unique:departments,code',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $designations = Designation::create([
            'designation_code' => $request->code,
            'designation_name' => $request->name,
            'status' => $request->status,
        ]);
        return redirect()->route('designations.index')->with('success', 'Designations created successfully.');
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
        // Find the department or fail
        $designations = Designation::findOrFail($id);


        // Return the same form view as create, passing department and units
        return view('designations.create', compact('designations'));
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
            'status' => 'required|in:active,inactive',
        ]);

        // Find the department
        $designations = Designation::findOrFail($id);

        // Update with validated data
        $designations->update([
            'designation_code' => $request->code,
            'designation_name' => $request->name,
            'status' => $request->status,
        ]);

        // Redirect back with success message
        return redirect()->route('designations.index')
                        ->with('success', 'Designations updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the department
        $designations = Designation::findOrFail($id);

        // Delete it
        $designations->delete();

        // Redirect back with success message
        return redirect()->route('designations.index')
                        ->with('success', 'Designations deleted successfully.');
    }
}
