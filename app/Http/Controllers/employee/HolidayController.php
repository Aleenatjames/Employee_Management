<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function create(){
        return view('employee.holidays.create');
    }
    public function index(){
        return view('employee.holidays.index');
    }
    public function edit($holidayId)
    {
    
    return view('employee.holidays.edit', compact('holidayId'));
    }
}
