<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/LeavesController.php

   public function index(Request $request)

    {
        $user = Auth::user();
        $view = $request->get('view', 'mine');

        // Pending count based on role
        $pendingCount = 0;

        if ($user->hasRole('admin')) {
            $pendingCount = Leave::where('status', 'pending')->count();
        } elseif ($user->hasRole('manager')) {
            // Correctly checks the manager_id in the employees table
            $pendingCount = Leave::whereHas('user.employees', function ($query) use ($user) {
                $query->where('manager_id', $user->employees->id);
            })
            ->where('status', 'pending')
            ->count();
        } elseif ($user->hasRole('hr')) {
            // HR sees leaves that a manager has already approved
            $pendingCount = Leave::where('status', 'manager approved')->count();
        } else {
            $pendingCount = 0; // Default to 0 if the user doesn't have a specific role
        }
        // Team view
        if ($view === 'team') {
            if ($user->hasRole('hr') || $user->hasRole('admin')) {
                // HR & Admin see all leaves
                $allLeaves = Leave::with('user')->latest()->get();

                return view('leaves.index', [
                    'leaves' => $allLeaves,
                    'user' => $user,
                    'view' => 'team',
                    'pendingCount' => $pendingCount,
                ]);
            } elseif ($user->hasRole('Manager')) {
                // Manager sees only their team leaves
                $teamLeaves = Leave::with('user')
                    ->whereHas(
                        'user',
                        fn($q) =>
                        $q->where('manager_id', $user->employee_id)
                    )
                    ->latest()
                    ->get();

                return view('leaves.index', [
                    'leaves' => $teamLeaves,
                    'user' => $user,
                    'view' => 'team',
                    'pendingCount' => $pendingCount,
                ]);
            }
        }

        // Default: My leaves
        $myLeaves = Leave::where('user_id', $user->id)->latest()->get();

        return view('leaves.index', [
            'leaves' => $myLeaves,
            'user' => $user,
            'view' => 'mine',
            'pendingCount' => $pendingCount,
        ]);
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
    // public function store(Request $request)
    // {
    //     // Validate form request
    //     $request->validate([
    //         'start_date'  => 'required|date',
    //         'end_date'    => 'required|date|after_or_equal:start_date',
    //         'reason'      => 'required|string|max:255',
    //         'leave_type'  => 'nullable|string|max:50',
    //     ]);

    //     // Get employee record of logged-in user
    //     $employee = Employees::where('user_id', Auth::id())->first();

    //     if (!$employee) {
    //         return back()->with('error', 'Employee record not found for this user.');
    //     }

    //     // ✅ Check duplicate or overlapping leave
    //     $duplicateLeave = Leave::where('emp_id', $employee->id)
    //         ->where(function ($query) use ($request) {
    //             $query->whereBetween('start_date', [$request->start_date, $request->end_date]) // overlap case
    //                 ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
    //                 ->orWhere(function ($q) use ($request) {
    //                     $q->where('start_date', '<=', $request->start_date)
    //                         ->where('end_date', '>=', $request->end_date); // fully inside another leave
    //                 });
    //         })
    //         ->exists();

    //     if ($duplicateLeave) {
    //         return back()->with('error', 'You already applied for leave in this date range.');
    //     }

    //     // Calculate total days
    //     $totalDays = Carbon::parse($request->start_date)
    //         ->diffInDays(Carbon::parse($request->end_date)) + 1;

    //     // Store leave request
    //     Leave::create([
    //         'emp_id'         => $employee->id,
    //         'start_date'     => $request->start_date,
    //         'end_date'       => $request->end_date,
    //         'total_days'     => $totalDays,
    //         'reason'         => $request->reason,
    //         'leave_type'     => $request->leave_type ?? null,
    //         'manager_status' => 'pending',
    //         'hr_status'      => 'pending',
    //         'manager_id'     => $employee->manager_id && Employees::find($employee->manager_id)
    //                                 ? $employee->manager_id
    //                                 : null,
    //     ]);

    //     return redirect()->route('leaves.index')
    //         ->with('success', 'Leave request submitted successfully!');
    // }
    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();

        $rules = [
            // 'leave_type' => 'required|in:casual,sick,earned,comp-off,od,permission',
            // 'leave_duration' => 'required|in:Full Day,Half Day',
            'leave_days' => 'required|numeric|min:0.5',
            'reason' => 'required|string|max:1000',
        ];

        if ($request->leave_type === 'comp-off') {
            $rules['comp_off_worked_date'] = 'required|date';
            $rules['comp_off_leave_date'] = 'required|date|after_or_equal:comp_off_worked_date';
        } else {
            $rules['start_date'] = 'required|date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        $validated = $request->validate($rules);

        // ✅ Check leave balance (not for comp-off)
        // if ($request->leave_type !== 'comp-off' && $user->leave_balance < $request->leave_days) {
        //     return back()->withInput()->with('error', 'Not enough leave balance.');
        // }

        // ✅ Check if leave date(s) clash with holidays (only for non comp-off)
        // if ($request->leave_type !== 'comp-off') {
        //     $holidayExists = \App\Models\Holiday::whereBetween('date', [$request->from_date, $request->to_date])->exists();
        //     if ($holidayExists) {
        //         return back()->withInput()->withErrors(['from_date' => 'Selected date(s) include holiday. Please choose another date.']);
        //     }
        // } else {
        //     // For comp-off, check comp_off_leave_date itself is not a holiday
        //     $isHoliday = \App\Models\Holiday::where('date', $request->comp_off_leave_date)->exists();
        //     if ($isHoliday) {
        //         return back()->withInput()->withErrors(['comp_off_leave_date' => 'Comp-off leave date is a holiday. Please choose another date.']);
        //     }
        // }

        // ✅ Save leave
        $leave = new Leave();
        $leave->user_id = $user->id;
        $leave->leave_type = $request->leave_type ?? 'N/A';
        $leave->leave_duration = $request->leave_duration ?? 'N/A';
        $leave->from_date = $request->start_date;
        $leave->to_date = $request->end_date;
        $leave->comp_off_worked_date = $request->comp_off_worked_date;
        $leave->comp_off_leave_date = $request->comp_off_leave_date;
        $leave->leave_days = $request->leave_days;
        $leave->reason = $request->reason;
        $leave->status = 'pending';
        $leave->save();

        // ✅ Deduct leave balance (not for comp-off)
        if ($request->leave_type !== 'comp-off') {
            $employee = Employees::where('user_id', $user->id)->first();
            if ($employee) {
                $employee->leave_balance -= $request->leave_days;
                $employee->save();
            }
        }

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted.');
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
    public function edit(Leave $leave)
    {
        // This method correctly uses Route Model Binding.
        // It's a great approach!
        $user = Auth::user();
        $availableLeaves = $user->leave_balance ?? 0;

        return view('leaves.edit', compact('leave', 'availableLeaves'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leave  $leave
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Leave $leave)
    {
        // The key change is in the method signature.
        // By changing `string $id` to `Leave $leave`, Laravel
        // automatically finds and injects the correct leave record.

        // First, validate the incoming request data.
        $request->validate([
            'leave_type' => 'required|string',
            'leave_duration' => 'required|string',
            'from_date' => 'required_if:leave_type,!=,comp-off|nullable|date',
            'to_date' => 'required_if:leave_type,!=,comp-off|nullable|date|after_or_equal:from_date',
            'comp_off_worked_date' => 'required_if:leave_type,comp-off|nullable|date',
            'comp_off_leave_date' => 'required_if:leave_type,comp-off|nullable|date',
            'reason' => 'required|string',
        ]);

        // Update the leave record with the new data from the request.
        $leave->leave_type = $request->input('leave_type');
        $leave->leave_duration = $request->input('leave_duration');
        $leave->from_date = $request->input('from_date');
        $leave->to_date = $request->input('to_date');
        $leave->comp_off_worked_date = $request->input('comp_off_worked_date');
        $leave->comp_off_leave_date = $request->input('comp_off_leave_date');
        $leave->reason = $request->input('reason');
        $leave->leave_days = $request->input('leave_days'); // Assuming this is calculated client-side
        $leave->save();

        // Redirect back to the index page with a success message.
        return redirect()->route('leaves.index')->with('success', 'Leave request updated successfully.');
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
