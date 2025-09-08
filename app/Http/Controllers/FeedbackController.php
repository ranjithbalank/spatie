<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return a view that lists feedback entries
        return view('feedbacks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('feedbacks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // @dd($request->all());
        // 1. Validate the incoming request data
        $request->validate([
            'feedback_type' => 'required|string',
            'areas_of_improvement' => 'required|string',
        ]);

        // 2. Create and save the new Feedback record
        Feedback::create([
            'user_id' => $request->emp_id, // Associate feedback with the authenticated user
            'feedback_text' => $request->feedback_type,
            'areas_of_improvement' => $request->areas_of_improvement,
        ]);

        // 3. Redirect the user back to the feedback list page with a success message
        return redirect()->route('feedback.index')->with('success', 'Feedback submitted successfully!');
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
