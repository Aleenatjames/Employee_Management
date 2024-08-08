<?php

namespace App\Livewire\ProjectAllocation;

use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectAllocation;
use App\Models\ProjectRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Edit extends Component
{
    public $allocationId;
    public $project_id;
    public $employee_id;
    public $role_id;
    public $allocated_by;
    public $start_date;
    public $end_date;

   

    public function mount($allocationId)
    {
        $allocation = ProjectAllocation::findOrFail($allocationId);
       
        $this->allocationId = $allocation->id;
        $this->project_id = $allocation->project_id;
        $this->employee_id = $allocation->employee_id;
        $this->role_id = $allocation->role_id;
        $this->allocated_by = $allocation->allocated_by;
        $this->start_date = $allocation->start_date;
        $this->end_date = $allocation->end_date;
    }

    public function update()
    {
        $this->validate();

        $allocation = ProjectAllocation::findOrFail($this->allocationId);
        $allocation->update([
            'project_id' => $this->project_id,
            'employee_id' => $this->employee_id,
            'role_id' => $this->role_id,
            'allocated_by' => $this->allocated_by,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,  
        ]);

        Session::flash('message', 'Project allocation updated successfully.');
        return redirect()->route('employee.projects.index');
    }

    public function render()
    {
        return view('livewire.project-allocation.edit', [
            'projects' => Project::all(),
            'employees' => Employee::all(),
            'roles' => ProjectRole::all(),
        ]);
    }
   
}
