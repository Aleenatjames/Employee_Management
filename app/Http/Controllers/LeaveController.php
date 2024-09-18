<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function list(){
        return view('employee.leave.list');
    }
}
