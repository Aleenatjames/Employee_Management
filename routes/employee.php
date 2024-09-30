<?php

use App\Http\Controllers\employee\AttendanceController;
use App\Http\Controllers\employee\HolidayController;
use App\Http\Controllers\employee\ProfileController;
use App\Http\Controllers\employee\ProjectController;
use App\Http\Controllers\employee\ProjectAllocation;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\employee\ProjectGroups;
use App\Http\Controllers\employee\TimesheetController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LeaveController;
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
    Route::get('/employee/time-entries', [TimesheetController::class,'create'])->name('employee.time-entries');
    Route::get('/employee/timesheet', [TimesheetController::class,'list'])->name('employee.timesheet');
    Route::get('/employee/timesheet-edit/{timesheet}', [TimesheetController::class,'edit'])->name('employee.timesheet.edit');
    Route::get('/export-timesheets', [TimesheetController::class, 'exportTimesheets'])->name('export.timesheets');
    
    Route::get('/holiday', [HolidayController::class,'index'])->name('holiday.index');
    Route::get('/holiday/create', [HolidayController::class,'create'])->name('holiday.create');
    // In web.php
    Route::get('holidays/edit/{holidayId}', [HolidayController::class,'edit'])->name('holiday.edit');

    Route::get('/employee-report', [TimesheetController::class,'index'])->name('employee-report.index');

    Route::get('/employee/attendance/table', [AttendanceController::class,'index'])->name('employee.attendance');
    Route::get('/employee/attendance/table/audit-history', [AttendanceController::class, 'history'])->name('employee.attendance.history');
    Route::post('/toggle-check-in-status', [AttendanceController::class, 'toggleCheckInStatus']);

    Route::post('/employee/attendance/table/update/{attendanceId}', [AttendanceController::class, 'update'])->name('employee.attendance.update');
  
    Route::get('/employee/attendance/view', [AttendanceController::class,'line'])->name('employee.attendance.line');
    Route::get('/employee/attendance/calendar', [AttendanceController::class,'calendar'])->name('employee.attendance.calendar');

    Route::get('/employee/leave/view', [LeaveController::class,'list'])->name('employee.leave');

    Route::get('/employee/profile/view',[ProfileController::class,'view'])->name('employee.profile');

});