<?php

namespace App\Livewire\ProjectTimesheet;

use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectTimesheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    public $project_id;
    public $date;
    public $time;
    public $time_hours;
    public $time_minutes;
    public $time_seconds;
    public $is_taskid = 0; // Default to No
    public $taskid;
    public $comment;

    public function mount()
    {
        // Optionally initialize properties
        $this->is_taskid = 0; // Default to No Task ID required
    }

    public function render()
    {
        $employee = Auth::guard('employee')->user();

        $projects = Project::whereHas('allocations', function ($query) use ($employee) {
            $query->where('employee_id', $employee->id);
        })->get();

        $projectGroups = ProjectGroup::where('isProject', 0)
        ->with('projects') // Eager load the projects
        ->get();

        return view('livewire.project-timesheet.create', [
            'projects' => $projects,
            'projectGroups' => $projectGroups,
        ]);
    }

    public function updatedIsTaskid($value)
    {
        // Reset the taskid if is_taskid is set to 'No'
        if ($value == 0) {
            $this->taskid = null;
        }
    }

    protected $rules = [
        'project_id' => 'required',
        'date' => 'required|date|before_or_equal:today',
        'time_hours' => 'nullable|integer|min:0',
        'time_minutes' => 'nullable|integer|min:0|max:59',
        'time_seconds' => 'nullable|integer|min:0|max:59',
        'comment' => 'nullable|string',
        'taskid' => 'required_if:is_taskid,1',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function submitTimesheet()
{
    $this->validate();

    // Extract project ID if it's a group-related project
    if (strpos($this->project_id, 'group-') !== false) {
        $projectId = str_replace('group-', '', $this->project_id);
    } else {
        $projectId = $this->project_id;
    }

    $project = Project::find($projectId);

    if (!$project) {
        session()->flash('message', 'Invalid project ID.');
        return;
    }

    // Additional checks for project groups (if needed)
    if ($project->group && $project->group->isProject == 0) {
        // Your custom logic for handling project groups
    }

    // Proceed with saving the timesheet data
    ProjectTimesheet::create([
        'employee_id' => Auth::guard('employee')->id(),
        'project_id' => $projectId,
        'date' => $this->date,
        'time' => sprintf('%02d:%02d:%02d', $this->time_hours, $this->time_minutes, $this->time_seconds),
        'is_taskid' => $this->is_taskid,
        'taskid' => $this->taskid,
        'comment' => $this->comment,
    ]);

    session()->flash('message', 'Timesheet submitted successfully.');
    return redirect()->route('employee.timesheet');
}


    public function resetForm()
    {
        $this->project_id = null;
        $this->date = null;
        $this->time_hours = null;
        $this->time_minutes = null;
        $this->time_seconds = null;
        $this->is_taskid = 0;
        $this->taskid = null;
        $this->comment = null;
    }
}
