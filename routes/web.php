<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\DashboardController; // <-- Make sure to import your controller

Route::get('/', function () {
    return redirect()->route('login');
});

// We apply the 'auth', 'verified', and our new 'check.status' middleware here
Route::middleware(['auth', 'verified', 'check.status'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');

Route::middleware('auth','menu')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource("roles", RoleController::class);
    Route::resource("permissions", PermissionController::class);
    Route::resource('menus', MenuController::class);
    Route::resource('menu-permission', MenuRolePermissionController::class);
    Route::resource("units",UnitController::class);
    Route::resource('departments',DepartmentController::class);
    Route::resource("users",UserController::class);
    // designations
    Route::resource("designations",DesignationController::class);
    // employees
    Route::resource('employees',EmployeeController::class);

    // Approval routes
    // Route::resource('leaves',LeaveController::class);
    // Route::post('leaves/{leave}/manager-decision', [LeaveController::class, 'managerDecision'])->name('leaves.manager.decision');
    // Route::post('leaves/{leave}/hr-decision', [LeaveController::class, 'hrDecision'])->name('leaves.hr.decision');

    // Holidays List
    Route::resource('holidays', HolidayController::class);
    Route::resource('internal-jobs', InternalJobPostingController::class);
    Route::post('/internal-jobs/apply/{job}', [InternalJobPostingController::class, 'apply'])->name('internal-jobs.apply');
    Route::get('/export-applicants', [InternalJobPostingController::class, 'exportApplicants'])->name('export.applicants');
    Route::get('/export-applicants-pdf', [InternalJobPostingController::class, 'exportApplicantsPdf'])->name('export.applicants.pdf');

    // ✅ Correct route method for file upload
    Route::post('/import-applicants-pdf', [InternalJobPostingController::class, 'uploadFinalStatus'])
        ->name('import.applicants.pdf');

    //leaves
    Route::resource("/leaves",LeaveController::class);
    Route::post('leaves/{leave}/manager-decision', [LeaveController::class, 'managerDecision'])->name('leaves.manager.decision');
    Route::post('leaves/{leave}/hr-decision', [LeaveController::class, 'hrDecision'])->name('leaves.hr.decision');
    Route::get('/leaves/export/excel', [LeaveExportController::class, 'exportExcel'])->name('leaves.export.excel');
    Route::get('/leaves/export/pdf', [LeaveExportController::class, 'exportPDF'])->name('leaves.export.pdf');

    // Circulars
    Route::resource('/circulars',CircularController::class);

    //events
    Route::resource('events', EventController::class);
    Route::get('/events-data', [EventController::class, 'fetchEvents'])->name('events.data');


    Route::get('/events/daily/{date}', [EventController::class, 'dailyEvents'])->name('events.daily');
});


require __DIR__.'/auth.php';
