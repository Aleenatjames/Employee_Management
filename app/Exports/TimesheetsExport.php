<?php

namespace App\Exports;

use App\Models\ProjectTimesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $selectedProject;
    protected $selectedEmployee;

    public function __construct($startDate = null, $endDate = null, $selectedProject = null, $selectedEmployee = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->selectedProject = $selectedProject;
        $this->selectedEmployee = $selectedEmployee;
    }

    public function collection()
    {
        $query = ProjectTimesheet::query()
            ->when($this->startDate, function ($query) {
                $query->where('date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->where('date', '<=', $this->endDate);
            })
            ->when($this->selectedProject, function ($query) {
                $query->where('project_id', $this->selectedProject);
            })
            ->when($this->selectedEmployee, function ($query) {
                $query->where('employee_id', $this->selectedEmployee);
            })
            ->with(['project', 'employee'])
            ->get();

       

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee Name',
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
            $timesheet->employee->name,
            $timesheet->date,
            $timesheet->taskid,
            $timesheet->time,
            $timesheet->comment,
            $timesheet->project->name ?? 'N/A',
        ];
    }
}
