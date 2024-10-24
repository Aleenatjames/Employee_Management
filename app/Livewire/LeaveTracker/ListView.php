<?php

namespace App\Livewire\LeaveTracker;

use App\Models\DivisionLeaveType;
use App\Models\Employee;
use App\Models\EmployeeDivision;
use App\Models\Holiday;
use App\Models\LeaveAllocation;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationDate;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ListView extends Component
{

    public $holidays=[];
    public $selectedEmployee;
    public $reportingEmployees;
    public $currentYear;
    public $yearRange; 
    public $sickLeaveAllocated;
    public $bookedSickLeave;
    public $casualLeaveAllocated;
    public $bookedCasualLeave;
    public $restrictedLeaveAllocated;
    public $bookedRestrictedLeave;
    public $leaveApplications=[];
    public $filtering = 'upcoming';
    public $selectedOption = 'All Leave and Holidays';
    public $leaveData=[];

    public function mount()
    {
        // Get the authenticated employee's ID
        $this->selectedEmployee = auth()->guard('employee')->user()->id;
    
        $this->currentYear = Carbon::now()->year;
        $this->generateYearRange();
        
        // Fetch the reporting employees for the selected employee
        $this->loadReportingEmployees();
        $this->showLeaveAndHolidays();
        $this->filter($this->filtering);
    }
    public function filter($filterType)
    {
        // Update the filter type and refresh the leave and holiday data
        $this->filtering = $filterType;
        $this->showLeaveAndHolidays();
    }
    public function setOption($option)
    {
        $this->selectedOption = $option;
        // You can add logic here to filter the data based on the option selected
    }
    public function showLeaveAndHolidays()
    {
        $today = Carbon::today();

        if ($this->filtering == 'upcoming') {
            // Fetch upcoming holidays and leave applications
            $this->holidays = Holiday::where('date', '>=', $today)
                ->where('is_restricted', 'no')
                ->orderBy('date')
                ->get();

            $this->leaveApplications = LeaveApplication::where('employee_id', $this->selectedEmployee)
                
                ->where('from_date', '>=', $today)
                ->orderBy('from_date')
                ->get();
        } else {
            // Fetch past holidays and leave applications
            $this->holidays = Holiday::where('date', '<=', $today)
                ->where('is_restricted', 'no')
                ->orderBy('date')
                ->get();

                $this->leaveApplications = LeaveApplication::where('employee_id', $this->selectedEmployee)
                ->where('from_date', '<=', $today)
                ->orderBy('from_date')
                ->get();            
        }
    }
    public function generateYearRange()
{
    $startOfYear = Carbon::create($this->currentYear, 1, 1)->format('d-m-Y');  // 01-01-YYYY
    $endOfYear = Carbon::create($this->currentYear, 12, 31)->format('d-m-Y');  // 31-12-YYYY

    $this->yearRange = $startOfYear . ' - ' . $endOfYear;
}
public function loadReportingEmployees()
{
    // Get the authenticated employee
    $employee = auth()->guard('employee')->user();
    $this->showLeaveAndHolidays();

    // Fetch reporting employees based on the authenticated employee's ID
    $this->reportingEmployees = Employee::where('reporting_manager', $employee->id)->get();

    // Initialize an associative array to hold leave type data dynamically
    $this->leaveData = [];

    // If a specific employee is selected, fetch their leave allocations
    if ($this->selectedEmployee) {
        // Fetch leave allocations for the selected employee
        $leaveAllocations = LeaveAllocation::where('employee_id', $this->selectedEmployee)->get();

        // Loop through each leave allocation and dynamically calculate the respective leaves
        foreach ($leaveAllocations as $leaveAllocation) {
            // Retrieve the leave type associated with this allocation
            $leaveType = LeaveType::find($leaveAllocation->leave_type);

            if ($leaveType) {
                // Use the leave type name as the key in the leaveData array
                $this->leaveData[$leaveType->name] = [
                    'allocated' => $leaveAllocation->allocated_days,  // Allocated days
                    'used' => $leaveAllocation->used                  // Booked/used days
                ];
            }
        }
    }
}

public function incrementLeaves()
{
    // Ensure the division ID is set before proceeding
    if (!$this->selectedEmployeeDivisionId) {
        Log::error('No division ID found for the selected employee.');
        return;
    }

    // Fetch the employee division details along with division child
    $employeeDivision = EmployeeDivision::with('divisionChild')
        ->where('employee_id', $this->selectedEmployee)
        ->first();

    if (!$employeeDivision || !$employeeDivision->divisionChild) {
        Log::error('No divisionChild found for the selected employee.');
        return;
    }

    // Get the child division ID
    $divisionChildId = $employeeDivision->divisionChild->id;

    // Fetch the leave types applicable for the employee's division
    $leaveTypes = LeaveType::where('applicable_division', $this->selectedEmployeeDivisionId)
        ->orWhereNull('applicable_division') // Include common leave types
        ->get();

    foreach ($leaveTypes as $leaveType) {
        // Only increment if is_carry_over is true
        if ($leaveType->is_carry_over) {
            // Fetch the corresponding division leave type
            $divisionLeaveType = DivisionLeaveType::where('leave_type', $leaveType->id)
                ->where('child_id', $divisionChildId) // Use the child division ID
                ->first();

            // If there is a valid division leave type
            if ($divisionLeaveType && isset($divisionLeaveType->incremental_count)) {
                $incrementalCount = $divisionLeaveType->incremental_count;
                $incrementalType = $leaveType->incremental_type; // Get the incremental type

                // Fetch the current leave allocation for the employee and leave type
                $leaveAllocation = LeaveAllocation::where('employee_id', $this->selectedEmployee)
                    ->where('leave_type', $leaveType->id)
                    ->first();

                if ($leaveAllocation) {
                    // Calculate and update available days based on the incremental type
                    switch ($incrementalType) {
                        case 'y':
                            // Increment yearly
                            $leaveAllocation->available_days += $incrementalCount; // Increment based on DivisionLeaveType
                            break;

                        case 'qua':
                            // Increment every quarter (4 quarters in a year)
                            $leaveAllocation->available_days += $incrementalCount * 3;
                            break;

                        case 'mon':
                            // Increment monthly (12 months in a year)
                            $leaveAllocation->available_days += $incrementalCount * 12;
                            break;

                        case 'h':
                            // Handle hourly increment if needed
                            $leaveAllocation->available_days += $incrementalCount; // Increment by hours
                            break;

                        default:
                            // Handle unknown incremental types
                            Log::warning("Unknown incremental type: {$incrementalType} for Leave Type ID: {$leaveType->id}");
                            break;
                    }

                    // Save the updated leave allocation
                    $leaveAllocation->save();

                    // Log the updated leave counts
                    Log::info("Incremented available leave days for Leave Type ID: {$leaveType->id} by {$incrementalCount} ({$incrementalType})");
                } else {
                    Log::warning("No leave allocation found for Employee ID: {$this->selectedEmployee} and Leave Type ID: {$leaveType->id}");
                }
            } else {
                Log::warning("No valid DivisionLeaveType found for Leave Type ID: {$leaveType->id} and Child Division ID: {$divisionChildId}");
            }
        }
    }
}


    public function previousPeriod(){
        $this->currentYear--;
        $this->generateYearRange();
    }

    public function nextPeriod(){
        $this->currentYear++;
        $this->generateYearRange();
    }
    
    public function render()
    {
        return view('livewire.leave-tracker.list-view');
    }


}
