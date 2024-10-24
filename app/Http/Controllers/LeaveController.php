<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function list(){
        return view('employee.leave.list');
    }
    public function division(){
        return view('employee.leave.division');
    }
    public function create(){
        return view('employee.leave.create');
    }
    public function index(){
        return view('employee.leave.index');
    }
    public function edit($leaveId)
    {
        return view('employee.leave.edit', compact('leaveId'));
    }
    public function apply(){
        return view('employee.leave.apply');
    }
    public function application(){
        return view('employee.leave.application');
    }
    public function show($applicationId){
        return view('employee.leave.show',compact('applicationId'));
    }
    

}
 