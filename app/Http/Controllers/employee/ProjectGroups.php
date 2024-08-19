<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectGroups extends Controller
{
    public function create(){
        return view('employee.project-groups.create');
    }
    public function index(){
        return view('employee.project-groups.index');
    }
    public function edit($groupId){
 
        return view('employee.project-groups.edit',compact('groupId'));
    }
   
}
