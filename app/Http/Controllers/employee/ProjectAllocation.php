<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\ProjectAllocation as ModelsProjectAllocation;
use Illuminate\Http\Request;

class ProjectAllocation extends Controller
{
    public function create(){
        return view('employee.project-allocation.create');

    }
    public function index(){
        return view('employee.project-allocation.index');

    }
    public function edit($allocationId){
 
        return view('employee.project-allocation.edit',compact('allocationId'));

    }
   
}
