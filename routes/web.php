<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuRolePermissionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');

Route::middleware('auth')->group(function () {
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

});



require __DIR__.'/auth.php';
