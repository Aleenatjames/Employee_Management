<?php
namespace App\Exports;

use App\Models\ProjectTimesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetsExport 
{
    protected $startDate;
    protected $endDate;
    protected $selectedProject;
    protected $selectedEmployee;

    public function __construct($startDate, $endDate, $selectedProject, $selectedEmployee)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->selectedProject = $selectedProject;
        $this->selectedEmployee = $selectedEmployee;
    }

    public function collection()
    {
        return ProjectTimesheet::query()
            ->where('employee_id', $this->selectedEmployee)
            ->when($this->startDate, function ($query) {
                $query->where('date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->where('date', '<=', $this->endDate);
            })
            ->when($this->selectedProject, function ($query) {
                $query->where('project_id', $this->selectedProject);
            })
            ->with(['project'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'Project ID',
            'Date',
            'Task ID',
            'Time',
            'Comment',
            'Project Name'
        ];
    }

    public function map($timesheet): array
    {
        return [
            $timesheet->id,
            $timesheet->employee_id,
            $timesheet->project_id,
            $timesheet->date,
            $timesheet->taskid,
            $timesheet->time,
            $timesheet->comment,
            $timesheet->project->name ?? 'N/A',
        ];
    }
}
