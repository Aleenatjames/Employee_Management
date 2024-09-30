<?php

namespace App\Livewire\ProjectTimesheet;

use App\Models\Project;
use App\Models\ProjectTimesheet;
use Livewire\Component;

class Edit extends Component
{
    public $timesheet_id;
    public $project_id;
    public $date;
    public $time_hours;
    public $time_minutes;
    public $time_seconds;
    public $is_taskid;
    public $taskid;
    public $comment;

    // Validation rules
    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'date' => 'required|date',
        'time_hours' => 'required|integer|min:0',
        'time_minutes' => 'nullable|integer|min:0|max:59',
        'time_seconds' => 'nullable|integer|min:0|max:59',
        'taskid'=>'required_if:is_taskid,1',
        'comment' => 'nullable|string',
    ];

    // Mounting the existing timesheet data
    public function mount($timesheet)
    {
        $timesheet=ProjectTimesheet::FindOrFail($timesheet);
        $this->timesheet_id = $timesheet->id;
        $this->project_id = $timesheet->project_id;
        $this->date = $timesheet->date;
        list($hours, $minutes, $seconds) = sscanf($timesheet->time, "%d:%d:%d");
        $this->time_hours = $hours;
        $this->time_minutes = $minutes;
        $this->time_seconds = $seconds;
        $this->is_taskid = $timesheet->taskid ? 1 : 0;
        $this->taskid = $timesheet->taskid;
        $this->comment = $timesheet->comment;
    }

    // Update Timesheet function
    public function updateTimesheet()
    {
        // Validate the form data
        $this->validate();
        $hours = str_pad($this->time_hours ?? 0, 2, '0', STR_PAD_LEFT);
        $minutes = str_pad($this->time_minutes ?? 0, 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($this->time_seconds ?? 0, 2, '0', STR_PAD_LEFT);
        $formattedTime = "{$hours}:{$minutes}:{$seconds}";

        // Find the existing timesheet entry
        $timesheet = ProjectTimesheet::findOrFail($this->timesheet_id);

        // Update the timesheet entry with the new data
        $timesheet->project_id = $this->project_id;
        $timesheet->date = $this->date;
        $timesheet->time = $formattedTime;
        $timesheet->taskid = $this->is_taskid ? $this->taskid : null;
        $timesheet->comment = $this->comment;
        $timesheet->save();

        // Set success message
        session()->flash('message', 'Timesheet updated successfully!');

       // Optionally, redirect to a different page or emit an event
        return redirect()->route('employee.timesheet');
    }

    public function render()
    {
        $projects = Project::all();

        return view('livewire.project-timesheet.edit', [
            'projects' => $projects,
        ]);
    }
}
    

