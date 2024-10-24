<?php

namespace App\Livewire\LeaveTracker;

use App\Models\Employee;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Application extends Component
{
    use WithPagination;

    public $perPage = 10; // Items per page
    public $search = '';   // Optional search functionality
    public $fromDate;      // Start date for filtering
    public $toDate;        // End date for filtering

    public function mount()
    {
        // Initialize any default data
    }

    public function updatingSearch()
    {
        // Reset pagination when searching
        $this->resetPage();
    }

    public function render()
    {
        $employeeId = auth()->guard('employee')->user()->id;
        
        // Fetch employees that are directly managed by this employee (if the employee is a manager)
        $managedEmployeeIds = Employee::where('reporting_manager', $employeeId)->pluck('id');
        
        // Fetch employees that are managed by the employees who report to this employee (manager's manager)
        $secondLevelManagedEmployeeIds = Employee::whereIn('reporting_manager', $managedEmployeeIds)->pluck('id');
        
        // Combine all employee IDs that this employee has authority over
        $allManagedEmployeeIds = $managedEmployeeIds->merge($secondLevelManagedEmployeeIds);
        
        // Fetch paginated leave applications for the authenticated employee and all the employees they manage or oversee
        $leaveApplications = LeaveApplication::where(function ($query) use ($employeeId, $allManagedEmployeeIds) {
                // Fetch the authenticated employee's own leave applications
                $query->where('employee_id', $employeeId);
    
                // Fetch leave applications for employees managed by the authenticated employee
                if ($allManagedEmployeeIds->isNotEmpty()) {
                    $query->orWhereIn('employee_id', $allManagedEmployeeIds);
                }
            })
            ->where(function ($query) {
                // Search by status
                $query->where('status', 'like', '%' . $this->search . '%');
    
                // Filter by fromDate
                if ($this->fromDate) {
                    $query->whereDate('from_date', '>=', $this->fromDate);
                }
    
                // Filter by toDate
                if ($this->toDate) {
                    $query->whereDate('to_date', '<=', $this->toDate);
                }
    
                // Filter by exact date match (if searching by a specific date)
                if ($this->search && !$this->fromDate && !$this->toDate) {
                    $query->whereDate('from_date', $this->search)
                          ->orWhereDate('to_date', $this->search);
                }
            })
            ->paginate($this->perPage);
    
        return view('livewire.leave-tracker.application', [
            'leaveApplications' => $leaveApplications,
        ]);
    }
    
    
}