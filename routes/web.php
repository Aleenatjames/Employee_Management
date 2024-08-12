<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}/delete', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');

     //Users
     Route::get('/users', [UserController::class, 'index'])->name('users.index');
    //  Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    //  Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
     Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
     Route::delete('/users/{id}/delete', [RoleController::class, 'destroy'])->name('users.destroy');

     //Company
     Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
     Route::get('/department/create', [DepartmentController::class, 'create'])->name('department.create');
     Route::post('/department', [DepartmentController::class, 'store'])->name('department.store');
     Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
     Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
     Route::delete('/department/{id}/delete', [DepartmentController::class, 'destroy'])->name('department.destroy');
     Route::put('department/toggle-status/{id}', [DepartmentController::class, 'toggleStatus'])->name('department.toggleStatus');

     //Employees
     Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
     Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
     Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
     Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
     Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
     Route::delete('/employees/{id}/delete', [EmployeeController::class, 'destroy'])->name('employees.destroy');
     Route::put('employees/toggle-status/{id}', [EmployeeController::class, 'toggleStatus'])->name('employees.toggleStatus');

     Route::get('/employees/login', [EmployeeController::class, 'login'])->name('employees.login');
});



require __DIR__.'/auth.php';
