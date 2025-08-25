<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\MenuRolePermissionController;
use App\Http\Controllers\DashboardController; // <-- Make sure to import your controller
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;

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
    Route::resource("designations",DesignationController::class);
    Route::resource('employees',EmployeeController::class);
    // Approval routes
    Route::resource('leaves',LeaveController::class);
    Route::put('leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::put('leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
});


require __DIR__.'/auth.php';
