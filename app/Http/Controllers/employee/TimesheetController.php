<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\ProjectTimesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function create(){
        return view('employee.project-timesheet.create');
    }
    public function list(){
        return view('employee.project-timesheet.index');
    }
    public function edit($timesheet){
        return view('employee.project-timesheet.edit',compact('timesheet'));
    }
   public function index(){
    return view('employee.report.index');
   }
}

