<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Employees;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use App\Models\TravelExpense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TravelExpenseController extends Controller
{
    /**
     * Display a listing of the travel expenses.
     */
    public function index()
    {
        $expenses_1 = TravelExpense::all();
        return view('travel_expenses.index', compact('expenses_1'));
    }

    /**
     * Show the form for creating a new travel expense.
     */
    public function create()
    {
        $user = Auth::user(); // Get the logged-in user

        // Eager load relationships if needed
        $user->load('employees');

        $units = Unit::all();
        $departments = Department::all();
        $designations = Designation::all();

        return view("travel_expenses.create", compact(
            'units',
            'user',
            // 'departments',
            // 'designations'
        ));
    }


    /**
     * Store a newly created travel expense in storage.
     */
    public function store(Request $request)
    {
        @dd($request->all());
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'unit_id' => 'required|exists:units,id',
            'designation_id' => 'required|exists:designations,id',
            'department_id' => 'required|exists:departments,id',
            'place_of_visit' => 'required|string|max:255',
            'purpose_of_visit' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'mode_of_travel' => 'required|string|max:255',
            'employee_signed' => 'required|boolean',
            'hod_approval_status' => 'in:approved,referred_for_exception,pending',
            'exception_approval_status' => 'in:approved,rejected,pending',
        ]);

        $expense = TravelExpense::create($validated);
        return redirect()->route('travel_expenses.index')->with('success', 'Travel expense submitted successfully.');
    }

    /**
     * Display the specified travel expense.
     */
    public function show(TravelExpense $travelExpense)
    {
        return view('travel_expenses.show', compact('travelExpense'));
    }

    /**
     * Show the form for editing the specified travel expense.
     */
    public function edit(TravelExpense $travelExpense)
    {
        return view('travel_expenses.edit', compact('travelExpense'));
    }

    /**
     * Update the specified travel expense in storage.
     */
    public function update(Request $request, TravelExpense $travelExpense)
    {
        $validated = $request->validate([
            'place_of_visit' => 'required|string|max:255',
            'purpose_of_visit' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'mode_of_travel' => 'required|string|max:255',
            'employee_signed' => 'required|boolean',
            'hod_approval_status' => 'in:approved,referred_for_exception,pending',
            'exception_approval_status' => 'in:approved,rejected,pending',
        ]);

        $travelExpense->update($validated);
        return redirect()->route('travel_expenses.index')->with('success', 'Travel expense updated successfully.');
    }

    /**
     * Remove the specified travel expense from storage.
     */
    public function destroy(TravelExpense $travelExpense)
    {
        $travelExpense->delete();
        return redirect()->route('travel_expenses.index')->with('success', 'Travel expense deleted successfully.');
    }
}
