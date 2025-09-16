<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $holidays = Holiday::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%");
        })
            ->orderBy('date', 'asc')
            ->paginate(5);

        return view('holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create holidays')) {
            return redirect()
                ->route('holidays.index')
                ->with('error', 'You do not have permission');
        }
        return view('holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'date' => 'required|date',
        ]);

        Holiday::create($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday created!');
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
    public function edit(Holiday $holiday)
    {
        if (!auth()->user()->can('edit holidays')) {
            return redirect()
                ->route('holidays.index')
                ->with('error', 'You do not have permission');
        }
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required',
            'date' => 'required|date',
        ]);

        $holiday->update($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted!');
    }
}
