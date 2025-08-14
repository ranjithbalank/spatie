<?php

namespace App\Http\Controllers;

use App\Models\Unit;

use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $units = Unit::when($search, function ($query, $search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        return view("units.index", compact("units", "search"));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("units.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:units,code',
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Unit::create([
            'code' => $request->code,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
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
        $unit = Unit::findOrFail($id);
        return view("units.edit", compact('unit'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1️⃣ Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // 2️⃣ Find the unit or fail
        $unit = Unit::findOrFail($id);

        // 3️⃣ Update the unit
        $unit->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // 4️⃣ Redirect back with success message
        return redirect()
            ->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // 1️⃣ Find the unit or fail
        $unit = Unit::findOrFail($id);

        // 2️⃣ Delete it
        $unit->delete();

        // 3️⃣ Redirect back with success message
        return redirect()
            ->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }

}
