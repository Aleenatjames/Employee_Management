<?php

use App\Http\Controllers\employee\ProjectController;
use App\Http\Controllers\employee\ProjectAllocation;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\employee\ProjectGroups;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;

//Employee Portal
Route::middleware('employee.redirect_if_authenticated')->group(function () {
    Route::get('/employee/login', [EmployeeController::class, 'login'])->name('employee.login');

    //Google Login
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google-auth');
    Route::get('/auth/google/call-back', [GoogleController::class, 'callbackGoogle']);
});

Route::middleware('employee.auth')->group(function () {
    Route::get('/', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
    Route::post('/employee/logout', [EmployeeController::class, 'logout'])->name('employee.logout');

   
    //Project
    Route::get('/employee/projects/index', [ProjectController::class,'index'])->name('employee.projects.index'); 

    // Route::group(['middleware' => ['role:project manager']], function () {

        Route::get('/employee/projects/create', [ProjectController::class,'create'])->name('employee.projects.create');
         Route::get('/employee/projects/{project}/edit', [ProjectController::class,'edit'])->name('employee.projects.edit');
     //Project Allocations
        Route::get('/employee/project-allocations/index', [ProjectAllocation::class,'index'])->name('employee.project-allocations.index');
        Route::get('/employee/project-allocations/create', [ProjectAllocation::class,'create'])->name('employee.project-allocations.create');
        Route::get('/employee/project-allocations/edit/{allocation}', [ProjectAllocation::class,'edit'])->name('employee.project-allocations.edit');

    //Project-Groups
        Route::get('/employee/project-groups/index', [ProjectGroups::class,'index'])->name('employee.project-groups.index');
        Route::get('/employee/project-groups/create', [ProjectGroups::class,'create'])->name('employee.project-groups.create');
        Route::get('/employee/project-groups/edit/{groupId}', [ProjectGroups::class,'edit'])->name('employee.project-groups.edit');
    // });  

  

    //Timesheets
    Route::get('/employee/time-entries', [ProjectGroups::class,'list'])->name('employee.time-entries');

});