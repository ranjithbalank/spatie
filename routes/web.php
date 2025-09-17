<?php

/**
 * ------------------------------------------------------------
 * Laravel Routes File (web.php)
 * ------------------------------------------------------------
 * Project          : MyDMW
 * Project Version  : 
 * Laravel Ver.     : 12.x
 * Description      : Defines all web routes including authentication,
 *                    admin panel, HR modules, job postings, leave 
 *                    management, and events.
 * Middleware       : auth, verified, role-based, menu
 * Maintainer       : Ranjithbalan / Saran karthick 
 * Version Ctrl     : Pre- Releases Versions
 * ------------------------------------------------------------
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CircularController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\LeaveExportController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\InternalJobPostingController;
use App\Http\Controllers\MenuRolePermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\TravelExpenseController;

/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
| Redirects base URL to login page.
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Protected by Middleware)
|--------------------------------------------------------------------------
| Middlewares:
|   - auth        : Only logged in users
|   - verified    : Email verified users only
|   - check.status: Custom middleware to check active status
*/
Route::middleware(['auth', 'verified', 'check.status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| User Import (CSV)
|--------------------------------------------------------------------------
*/
Route::get('import', [UserController::class, 'import_csv'])->name('users.import_form');
Route::post('import', [UserController::class, 'import'])->name('users.import');

/*
|--------------------------------------------------------------------------
| Admin Section
|--------------------------------------------------------------------------
| Admin dashboard route (only accessible to users with 'admin' role).
*/
Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes with Menu Middleware
|--------------------------------------------------------------------------
| Applies:
|   - auth : Requires authentication
|   - menu : Custom middleware (menu visibility/permissions)
*/
Route::middleware(['auth', 'menu'])->group(function () {

    /** -------------------------
     *  Profile Management
     * --------------------------*/
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** -------------------------
     *  Role & Permission System
     * --------------------------*/
    Route::resource("roles", RoleController::class);
    Route::resource("permissions", PermissionController::class);

    /** -------------------------
     *  Menu & Menu Permissions
     * --------------------------*/
    Route::resource('menus', MenuController::class);
    Route::resource('menu-permission', MenuRolePermissionController::class);

    /** -------------------------
     *  Organization Structure
     * --------------------------*/
    Route::resource("units", UnitController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource("designations", DesignationController::class);
    Route::resource('employees', EmployeeController::class);

    /** -------------------------
     *  User Management
     * --------------------------*/
    Route::resource("users", UserController::class);

    /** -------------------------
     *  Holiday Management
     * --------------------------*/
    Route::resource('holidays', HolidayController::class);

    /** -------------------------
     *  Internal Job Posting (IJP)
     * --------------------------*/
    Route::resource('internal-jobs', InternalJobPostingController::class);
    Route::post('/internal-jobs/apply/{job}', [InternalJobPostingController::class, 'apply'])->name('internal-jobs.apply');
    Route::get('/export-applicants', [InternalJobPostingController::class, 'exportApplicants'])->name('export.applicants');
    Route::get('/export-applicants-pdf', [InternalJobPostingController::class, 'exportApplicantsPdf'])->name('export.applicants.pdf');

    // ✅ Upload Final Job Status (Excel/PDF Import)
    Route::post('/import-applicants-pdf', [InternalJobPostingController::class, 'uploadFinalStatus'])
        ->name('import.applicants.pdf');

    /** -------------------------
     *  Leave Management
     * --------------------------*/
    Route::resource("/leaves", LeaveController::class);
    Route::post('leaves/{leave}/manager-decision', [LeaveController::class, 'managerDecision'])->name('leaves.manager.decision');
    Route::post('leaves/{leave}/hr-decision', [LeaveController::class, 'hrDecision'])->name('leaves.hr.decision');
    Route::get('/leaves/export/excel', [LeaveExportController::class, 'exportExcel'])->name('leaves.export.excel');
    Route::get('/leaves/export/pdf', [LeaveExportController::class, 'exportPDF'])->name('leaves.export.pdf');

    /** -------------------------
     *  Circulars / Notices
     * --------------------------*/
    Route::resource('/circulars', CircularController::class);

    /** -------------------------
     *  Events & Calendar
     * --------------------------*/
    Route::resource('events', EventController::class);
    Route::get('/events-data', [EventController::class, 'fetchEvents'])->name('events.data');
    Route::get('/events/daily/{date}', [EventController::class, 'dailyEvents'])->name('events.daily');

    /** -------------------------
     *  Feedback
     * --------------------------*/
    Route::resource('feedback', FeedbackController::class);

    /** -------------------------
     *  Travel Expenses
     * --------------------------*/
    Route::resource('travel_expenses', TravelExpenseController::class);
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
