<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        return view('employee.projects.index');
    }
    public function create(){
        return view('employee.projects.create');
    }
    public function edit($id)
    {
    $project = Project::findOrFail($id);
    return view('employee.projects.edit', compact('project'));
    }
}
