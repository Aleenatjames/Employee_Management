<?php

namespace App\Exports;

use App\Models\ProjectAllocation;
use App\Models\ProjectTimesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectAllocationExport implements FromCollection , WithHeadings, WithMapping
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
        $query = ProjectAllocation::query()
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
            ->with(['project', 'employee','role','allocatedBy'])
            ->get();

       

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Project Name',
            'Employee Name',
            'Role',
            'Start Date',
            'End Date',
            'Allocated By',
            
        ];
    }
    public function map($allocation): array
    {
        return [
            $allocation->id,
            $allocation->project->name,
            $allocation->employee->name,
            $allocation->role->name,
            $allocation->start_date,
            $allocation->end_date,
            $allocation->allocatedBy->name,
        ];
    }
}
