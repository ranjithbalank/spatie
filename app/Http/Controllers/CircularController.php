<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ------------------------------------------------------------
 * Controller: CircularController
 * ------------------------------------------------------------
 * Purpose:
 *   - Manage CRUD operations for Circulars (official notices).
 *   - On create, also logs circulars into the Events module.
 *
 * Version Control:
 *   - Commit msg example: "controller: add circulars CRUD"
 *   - Keep related changes (views/migrations/models) in same commit.
 *   - Do not overwrite existing methods; add new commits for changes.
 *
 * Author       : Your Name / Team
 * Laravel Ver. : 12.x
 * ------------------------------------------------------------
 */
class CircularController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all circulars sorted by date (latest first)
        $circulars = Circular::orderBy('circular_date', 'desc')->get();

        return view('circular.index', compact('circulars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * RBAC: Only users with 'create circulars' permission can access.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if (!auth()->user()->can('create circulars')) {
            return redirect()
                ->route('circulars.index')
                ->with('error', 'You do not have permission');
        }

        return view('circular.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validates input, uploads file, creates Circular,
     * and also inserts an Event entry for calendar sync.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'circular_number' => 'required|string|max:255',
            'circular_name'   => "required|string|max:255",
            'circular_date'   => 'required|date',
            'circular_file'   => 'required|file|mimes:pdf', // PDF only
        ]);

        // Handle file upload
        if ($request->hasFile('circular_file')) {
            // Save file to storage/app/public/circulars
            $path = $request->file('circular_file')->store('circulars', 'public');
        } else {
            return back()->with('error', 'No file was uploaded.');
        }

        // Create a new Circular entry
        Circular::create([
            'circular_no'   => $request->circular_number,
            'circular_name' => $request->circular_name,
            'circular_date' => $request->circular_date,
            'created_by'    => Auth::user()->name,
            'file_path'     => $path,
        ]);

        // Log circular as an event (calendar integration)
        Event::create([
            "title"       => $request->circular_name,
            "description" => $request->circular_name,
            "start"       => $request->circular_date,
            "end"         => $request->circular_date,
            "color"       => "#FF5733"
        ]);

        return redirect()->route('circulars.index')->with('success', 'Circular uploaded successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Circular  $Circular
     */
    public function show(Circular $Circular)
    {
        // TODO: Implement show view if needed
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Circular  $Circular
     */
    public function edit(Circular $Circular)
    {
        // TODO: Implement edit view if needed
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Circular      $Circular
     */
    public function update(Request $request, Circular $Circular)
    {
        // TODO: Implement update logic if needed
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Circular  $Circular
     */
    public function destroy(Circular $Circular)
    {
        // TODO: Implement delete logic if needed
    }
}
