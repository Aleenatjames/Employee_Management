<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class CreateProject extends Component
{
    public $name;
    public $description;
    public $status = 'active';
    public $start_date;
    public $end_date;
    public $pm;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:1000',
        'status' => 'required|in:active,inactive',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'pm' => 'required|exists:employees,id'
    ];

    public function save()
    {
        $this->validate();

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pm' => $this->pm
        ]);

        Session::flash('message', 'Project created successfully.');
        return redirect()->route('employee.projects.index');

        $this->reset(); // Reset the form fields
    }

    public function render()
    {
        return view('livewire.projects.create-project');
    }
}
