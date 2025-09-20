<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $view = $request->get('view', 'mine');
        $user_leaves = Auth::user()->leave_balance;
        // dd($user_leaves);
        // Pending count based on role
        $pendingCount = 0;

        if ($user->hasRole('admin')) {
            // Admin sees all pending leaves
            $pendingCount = Leave::where('status', 'pending')->count();
        } elseif ($user->hasRole('manager')) {
            // Get logged-in manager's emp_id
            $managerEmpId = DB::table('employees_details')
                ->where('user_id', $user->id)
                ->value('emp_id');

            // Manager sees only team members' pending leaves
            $pendingCount = Leave::join('employees_details', 'employees_details.user_id', '=', 'leaves.user_id')
                ->where('employees_details.manager_id', $managerEmpId)
                ->where('leaves.status', 'pending')
                ->count();
        } elseif ($user->hasRole('hr')) {
            // ✅ NEW: Get the HR's unit_id
            $hrUnitId = DB::table('employees_details')
                ->where('user_id', $user->id)
                ->value('unit_id');

            // ✅ NEW: HR sees leaves that are supervisor/manager approved within their unit
            $pendingCount = Leave::join('employees_details', 'employees_details.user_id', '=', 'leaves.user_id')
                ->where('employees_details.unit_id', $hrUnitId)
                ->where('leaves.status', 'supervisor/ manager approved')
                ->count();
        }

        // Team view
        if ($view === 'team') {
            if ($user->hasRole('admin') || $user->employees->unit_id == 1) {
                // Admin sees all team leaves
                $allLeaves = Leave::with('user')->latest()->get();

                return view('leaves.index', [
                    'leaves' => $allLeaves,
                    'user' => $user,
                    'view' => 'team',
                    'pendingCount' => $pendingCount,
                ]);
            } elseif ($user->hasRole('hr')) {
                // ✅ NEW: Get the HR's unit_id
                $hrUnitId = DB::table('employees_details')
                    ->where('user_id', $user->id)
                    ->value('unit_id');

                // ✅ NEW: HR sees only their unit's leaves
                $allLeaves = Leave::with('user')
                    ->join('employees_details', 'employees_details.user_id', '=', 'leaves.user_id')
                    ->where('employees_details.unit_id', $hrUnitId)
                    ->latest('leaves.created_at')
                    ->get(['leaves.*']);

                return view('leaves.index', [
                    'leaves' => $allLeaves,
                    'user' => $user,
                    'view' => 'team',
                    'pendingCount' => $pendingCount,
                ]);
            } elseif ($user->hasRole('manager')) {
                // Get logged-in manager's emp_id
                $managerEmpId = DB::table('employees_details')
                    ->where('user_id', $user->id)
                    ->value('emp_id');

                // Manager sees only their team's leaves
                $teamLeaves = Leave::with('user')
                    ->join('employees_details', 'employees_details.user_id', '=', 'leaves.user_id')
                    ->where('employees_details.manager_id', $managerEmpId)
                    ->latest('leaves.created_at')
                    ->get(['leaves.*']); // select only leaves columns

                return view('leaves.index', [
                    'leaves' => $teamLeaves,
                    'user' => $user,
                    'view' => 'team',
                    'pendingCount' => $pendingCount,
                ]);
            }
        }

        // Default: My leaves (for normal employees)
        $myLeaves = Leave::where('user_id', $user->id)->latest()->get();

        // // employees count
        // $employeesCount = DB::table('employees_details')->count();
        // $activeEmployeesCount = DB::table('employees_details')
        //     ->join('users', 'employees_details.user_id', '=', 'users.id')
        //     ->where('users.status', 1)
        //     ->count();
        $ijpCount = DB::table('internal_jobpostings')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->count();

        $holidays = DB::table('holidays')
            ->where('date', '>=', now()->toDateString())
            ->get();

        $circular = DB::table("circulars")
            ->get();

        $tickets = DB::table("feedback")->count();

        return view('dashboard', [
            'leaves' => $myLeaves,
            'user_leave' => $user_leaves,
            'user' => $user,
            'view' => 'mine',
            'pendingCount' => $pendingCount,
            'ijpCount' => $ijpCount,
            'holidays' => $holidays,
            'circulars' => $circular,
            "tickets" => $tickets
            // 'employeesCount' => $employeesCount,
            // 'activeEmployeesCount' => $activeEmployeesCount,
        ]);
    }
}
