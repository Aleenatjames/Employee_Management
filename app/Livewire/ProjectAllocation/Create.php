<?php

namespace App\Livewire\ProjectAllocation;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectAllocation;
use App\Models\ProjectRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Create extends Component
{
    public $project_id;
    public $employee_id;
    public $role_id;
    public $start_date;
    public $end_date;

    public $projects;
    public $employees;
    public $roles;

    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'employee_id' => 'required|exists:employees,id',
        'role_id' => 'required|exists:project_roles,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function mount()
    {
        $this->projects = Project::all();
        $this->employees = Employee::all();
        $this->roles = ProjectRole::all();
    }

    public function submit()
    {
        $this->validate();

        ProjectAllocation::create([
            'project_id' => $this->project_id,
            'employee_id' => $this->employee_id,
            'role_id' => $this->role_id,
            'allocated_by' => Auth::guard('employee')->id(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        Session::flash('message', 'Project allocation created successfully.');
        return redirect()->route('employee.project-allocations.index');

        // Reset fields after submission
        $this->reset();
    }

   
    public function render()
    {
        return view('livewire.project-allocation.create');
    }
}
