<?php

namespace App\Livewire\LeaveTracker;

use App\Models\Employee;
use App\Models\LeaveAllocation;
use App\Models\LeaveApplication;
use App\Models\LeaveApplicationDate;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Apply extends Component
{
    public $employee;
    public $leave_type;
    public $date_from;
    public $date_to;
    public $reason;
    public $totalDays = 0;

    // Validation Rules
    protected $rules = [
        'employee' => 'required|exists:employees,id',
        'leave_type' => 'required|exists:leave_types,id',
        'date_from' => 'required|date',
        'date_to' => 'required|date|after_or_equal:date_from',
        'reason' => 'required|string|max:255',
    ];
    public $selectedDates = [];
    public $dayType = [];
    public $showModal = true;

    public function updated($propertyName)
    {
        if ($this->date_from && $this->date_to) {
            $this->calculateDatesInRange(); // Calculate the date range
            $this->openModal(); // Show the modal
        }
    }
    public function mount() {}

    // Function to calculate all dates between date_from and date_to
    public function calculateDatesInRange()
    {
        $startDate = new \DateTime($this->date_from);
        $endDate = new \DateTime($this->date_to);
        $endDate = $endDate->modify('+1 day');

        $dateRange = [];
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $dayOfWeek = $date->format('N'); // 6 = Saturday, 7 = Sunday

            // Check if the date is a weekend
            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                $this->dayType[$formattedDate] = 'weekend'; // Mark as weekend
            }

            // Store the formatted date
            $dateRange[] = $formattedDate;
        }

        $this->selectedDates = $dateRange; // Store the calculated date range
    }




    public function calculateTotalDays()
    {
        $this->totalDays = 0; // Reset total days

        foreach ($this->dayType as $date => $type) {
            if ($type == '0') {
                // Full day
                $this->totalDays += 1;
            } elseif ($type == '1' || $type == '2') {
                // First Half or Second Half
                $this->totalDays += 0.5;
            }
        }
    }
    public function updatedDayType($value, $key)
    {
        $this->calculateTotalDays();
    }

    public function openModal()
    {
        $this->showModal = true; // Show the modal
    }

    public function closeModal()
    {
        $this->showModal = false; // Hide the modal
    }
    public function submit()
    {
        $this->validate([
            'employee' => 'required|exists:employees,id',
            'leave_type' => 'required|exists:leave_types,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'reason' => 'required|string',
        ]);
    
        // Log for debugging
        Log::info('Submitting leave application', [
            'employee' => $this->employee,
            'leave_type' => $this->leave_type,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'reason' => $this->reason,
        ]);
    
        try {
            // Check if employee has allocated leave days
            $leaveAllocation = LeaveAllocation::where('employee_id', $this->employee)
                ->where('leave_type', $this->leave_type)
                ->first();
    
            if ($leaveAllocation && $leaveAllocation->allocated_days > 0) {
    
                // Filter out weekends from selectedDates
                $validDates = array_filter($this->selectedDates, function($date) {
                    $dayOfWeek = (new \DateTime($date))->format('N'); // 6 = Saturday, 7 = Sunday
                    return $dayOfWeek < 6; // Return true for weekdays (1-5)
                });
    
                if (count($validDates) > 0) {
                    // Calculate total leave days based on full-day and half-day types
                    $this->calculateTotalDays(); // Calculate total days using the function
                    
                    // Check if the employee has enough allocated days
                    if ($this->totalDays <= $leaveAllocation->allocated_days) {
                        // Create Leave Application
                        $leaveApplication = LeaveApplication::create([
                            'employee_id' => $this->employee,
                            'leave_type' => $this->leave_type,
                            'from_date' => $this->date_from,
                            'to_date' => $this->date_to,
                            'reason' => $this->reason,
                            'no_of_days' => $this->totalDays, // Use the total days calculated
                            'lastupdated_by' => auth()->guard('employee')->user()->id,
                            'status' => 'pending', // Default status
                        ]);
    
                        // Increment the used days in the leave allocation
                        $leaveAllocation->used += $this->totalDays;
    
                        // Update the allocated days (remaining leave)
                        $leaveAllocation->allocated_days = $leaveAllocation->allocated_days - $this->totalDays;
                        $leaveAllocation->save(); // Save the updated leave allocation
                        
                        // Save each valid date and its duration to the database
                        foreach ($validDates as $date) {
                            $leaveDuration = $this->dayType[$date]; // Full day or half-day type
                            
                            // Save to the LeaveApplicationDate table
                            LeaveApplicationDate::create([
                                'application_id' => $leaveApplication->id,
                                'date' => $date,
                                'duration' => $leaveDuration, // Save '0' for Full Day, '1' for First Half, '2' for Second Half
                            ]);
                        }
    
                        session()->flash('message', 'Leave application submitted successfully.');
                    } else {
                        session()->flash('error', 'Not enough allocated leave days available.');
                    }
                } else {
                    session()->flash('error', 'No valid leave days (excluding weekends) selected.');
                }
            } else {
                session()->flash('error', 'No allocated days available for this leave type.');
            }
        } catch (\Exception $e) {
            Log::error('Error creating leave application:', ['exception' => $e->getMessage()]);
            session()->flash('error', 'There was an error submitting your leave application. Please try again.');
        }
    }
    

    public function render()
    {
        $loggedInEmployee = auth()->guard('employee')->user();

        // Get employees who report to the logged-in employee
        $reportingEmployees = Employee::where('reporting_manager', $loggedInEmployee->id)->get();

        // Merge the logged-in employee with their reporting employees
        $employees = collect([$loggedInEmployee])->merge($reportingEmployees);

        $leave_types = LeaveType::whereIn('id', function ($query) use ($employees) {
            $query->select('leave_type')
                ->from('leave_allocations')
                ->whereIn('employee_id', $employees->pluck('id'));
        })->get();


        return view('livewire.leave-tracker.apply', [
            'employees' => $employees,
            'leave_types' => $leave_types,

        ]);
    }
}
