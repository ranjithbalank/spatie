<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the employee linked to the logged-in user
        $employee = Employees::where('user_id', Auth::id())->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found.');
        }

        // Fetch only that employee’s leaves
        $appliedLeaves = Leave::where('emp_id', $employee->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Manager/HR approval queue (optional)
        $approvalLeaves = Leave::where('manager_id', $employee->id)
                            ->where('manager_status', 'pending')
                            ->get();

        return view('leaves.index', compact('appliedLeaves', 'approvalLeaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employees::with('designation')->get();
        return view("leaves.create", compact('employees'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate form request
        $request->validate([
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:255',
            'leave_type'  => 'nullable|string|max:50',
        ]);

        // Get employee record of logged-in user
        $employee = Employees::where('user_id', Auth::id())->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not found for this user.');
        }

        // ✅ Check duplicate or overlapping leave
        $duplicateLeave = Leave::where('emp_id', $employee->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date]) // overlap case
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date); // fully inside another leave
                    });
            })
            ->exists();

        if ($duplicateLeave) {
            return back()->with('error', 'You already applied for leave in this date range.');
        }

        // Calculate total days
        $totalDays = Carbon::parse($request->start_date)
            ->diffInDays(Carbon::parse($request->end_date)) + 1;

        // Store leave request
        Leave::create([
            'emp_id'         => $employee->id,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'total_days'     => $totalDays,
            'reason'         => $request->reason,
            'leave_type'     => $request->leave_type ?? null,
            'manager_status' => 'pending',
            'hr_status'      => 'pending',
            'manager_id'     => $employee->manager_id && Employees::find($employee->manager_id)
                                    ? $employee->manager_id
                                    : null,
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully!');
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
        // Find leave by ID
        $leave = Leave::findOrFail($id);

        // (Optional) Check if logged-in user owns the leave
        // if ($leave->emp_id !== auth()->user()->employee->id) {
        //     return redirect()->route('leaves.index')
        //         ->with('error', 'You are not allowed to delete this leave request.');
        // }

        // Delete the record
        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->manager_status = 'approved'; // or HR if HR approves
        $leave->save();

        return redirect()->route('leaves.index')->with('success', 'Leave approved successfully.');
    }

    public function reject($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->manager_status = 'rejected'; // or HR if HR rejects
        $leave->save();

        return redirect()->route('leaves.index')->with('error', 'Leave rejected.');
    }
}
