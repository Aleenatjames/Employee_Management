<?php

namespace App\Livewire\Projects;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class EditProject extends Component
{
    public $project;
    public $name;
    public $description;
    public $status;
    public $start_date;
    public $end_date;
    public $pm;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->status = $project->status;
        $this->start_date = $project->start_date;
        $this->end_date = $project->end_date;
        $this->pm = $project->pm;
       
}
    

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pm' => 'required|exists:employees,id',
        ]);

        $this->project->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pm' => $this->pm,
        ]);

        Session::flash('message', 'Project updated successfully.');
        return redirect()->route('employee.projects.index'); // or wherever you want to redirect
    }

    public function render()
    {
        return view('livewire.projects.edit-project', [
            'employees' => Employee::all(),
        ]);
    }
}
