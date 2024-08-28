<?php

namespace App\Livewire\EmployeeReport;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\ProjectTimesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // New properties for pagination and filtering
    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $startDate;
    public $endDate;
    public $selectedProject = '';
    public $selectedEmployee;
    public $selectedEmployees = [];
    public $timesheets;


    public function mount($startDate = null, $endDate = null, $selectedProjects = [])
    {
        $employeeId = Auth::guard('employee')->id();
        $employee = Employee::find($employeeId);
    
        $this->selectedEmployee = $employeeId;
    
        // Ensure startDate does not exceed employee creation date
        $createdAt = $employee->created_at->format('Y-m-d');
        $this->startDate = $employee->created_at->format('Y-m-d');
    
        $this->endDate = $endDate ?? Carbon::now()->format('Y-m-d');
        $this->selectedProject = $selectedProjects;
    
        // Initialize selected employees to include the authenticated employee
        $this->selectedEmployees = [$employeeId];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    public function updatedSelectedEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);
        
        if ($employee) {
            $this->startDate = $employee->created_at->format('Y-m-d');
        }
    }
    public function render()
{
    $employee = Auth::guard('employee')->user();
    $reportingManager = $employee->reportingManager; // This retrieves the reporting manager for the logged-in employee

    $employeeId = $employee->id;

    // Fetch projects with allocations or those belonging to groups with a specific group_id
    $projects = Project::whereHas('allocations', function ($query) use ($employeeId) {
        $query->where('employee_id', $employeeId);
    })
    ->orWhereHas('group', function ($query) {
        $query->whereNotNull('group_id');
    })
    ->get();

    $currentDate = now()->format('Y-m-d');

    // Determine the target employee ID(s)
    if ($this->selectedEmployee === '' || $this->selectedEmployee === 'all') {
        $targetEmployeeIds = Employee::where('reporting_manager', $employeeId)->pluck('id')->toArray();
        $selectedEmployeeDetails = null; // No specific employee selected
    } else {
        $targetEmployeeIds = [$this->selectedEmployee ?? $employeeId];
        $selectedEmployeeDetails = Employee::find($this->selectedEmployee); // Fetch the selected employee's details
    }

    // Fetch all timesheets based on the applied filters
    $timesheetsQuery = ProjectTimesheet::query()
        ->whereIn('employee_id', $targetEmployeeIds)
        ->when($this->startDate, function ($query) {
            $query->where('date', '>=', $this->startDate);
        })
        ->when($this->endDate, function ($query) {
            $query->where('date', '<=', $this->endDate);
        })
        ->when($this->selectedProject, function ($query) {
            $query->where('project_id', $this->selectedProject);
        });

    // Get the timesheets
    $timesheets = $timesheetsQuery->get();

    // Calculate the total time worked
    $totalTime = $this->calculateTotalTime($timesheets);

    // Calculate the number of working days (excluding holidays)
    $workingDays = $this->calculateWorkingDays($this->startDate, $this->endDate);

    // Assuming 8 hours per working day
    $expectedHours = $workingDays * 8;

    // Convert total time from HH:MM format to decimal hours
    $totalTimeDecimal = 0;
    foreach ($timesheets as $timesheet) {
        $totalTimeDecimal += $this->convertTimeToDecimal($timesheet->time);
    }

    // Calculate deviation
    $deviation = $totalTimeDecimal - $expectedHours;

    // Fetch the reporting employees
    $reportingEmployees = Employee::where('reporting_manager', $employeeId)->get();

    // Variables for project hours and category hours
    $projectHours = 0;
    $trainingHours = 0;
    $beachHours = 0;
    $learningHours = 0;
    $leaveDays = 0;

    foreach ($projects as $project) {
        if ($project->group_id) {
            if ($project->isProject == 1) {
                $projectHours += $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                    return $this->convertTimeToDecimal($timesheet->time);
                });
            } else {
                switch ($project->group_id) {
                    case 4: // Training group ID
                        $trainingHours += $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                            return $this->convertTimeToDecimal($timesheet->time);
                        });
                        break;
                    case 2: // Beach group ID
                        $beachHours += $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                            return $this->convertTimeToDecimal($timesheet->time);
                        });
                        break;
                    case 6: // Learning group ID
                        $learningHours += $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                            return $this->convertTimeToDecimal($timesheet->time);
                        });
                        break;
                    case 3: // Leave group ID
                        $totalLeaveHours = $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                            return $this->convertTimeToDecimal($timesheet->time);
                        });
                        $leaveDays += $totalLeaveHours / 8;
                        break;
                }
            }
        } else {
            $projectHours += $timesheets->where('project_id', $project->id)->sum(function ($timesheet) {
                return $this->convertTimeToDecimal($timesheet->time);
            });
        }
    }

    return view('livewire.employee-report.index', [
        'timesheets' => $timesheets,
        'totalTime' => $totalTime,
        'projects' => $projects,
        'reportingEmployees' => $reportingEmployees,
        'employee' => $employee,
        'reportingManager' => $reportingManager,
        'deviation' => $this->convertDecimalToTime($deviation),
        'expectedHours' => $this->convertDecimalToTime($expectedHours),
        'trainingHours' => $this->convertDecimalToTime($trainingHours),
        'beachHours' => $this->convertDecimalToTime($beachHours),
        'learningHours' => $this->convertDecimalToTime($learningHours),
        'projectHours' => $this->convertDecimalToTime($projectHours),
        'leaveDays' => number_format($leaveDays, 2),
        'selectedEmployeeDetails' => $selectedEmployeeDetails // Pass the selected employee's details to the view
    ]);
}


    /**
     * Calculate the number of working days between two dates excluding holidays.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    protected function calculateWorkingDays($startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $end->modify('+1 day'); // Include the end date in the calculation

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);

        $workingDays = 0;

        foreach ($dateRange as $date) {
            // Exclude weekends (Saturday and Sunday) and holidays
            if ($date->format('N') < 6 && !$this->isHoliday($date->format('Y-m-d'))) {
                $workingDays++;
            }
        }

        return $workingDays;
    }

    /**
     * Check if the given date is a holiday.
     *
     * @param string $date
     * @return bool
     */
    protected function isHoliday($date)
    {
        return Holiday::where('date', $date)->exists();
    }

    /**
     * Convert time format (HH:MM) to decimal hours.
     *
     * @param string $time
     * @return float
     */
    protected function convertTimeToDecimal($time)
    {
        list($hours, $minutes) = explode(':', $time);
        return  $hours + ($minutes / 60);
    }

    protected function convertDecimalToTime($decimal)
    {
        // Ensure the input is treated as a float
        $decimal = (float) $decimal;

        // Calculate hours and minutes
        $hours = floor($decimal);
        $minutes = ($decimal - $hours) * 60;

        // Return time in HH:MM format
        return sprintf('%02d:%02d', $hours, abs($minutes));
    }

    /**
     * Sum two time values in HH:MM format.
     *
     * @param string $time1
     * @param string $time2
     * @return string
     */
    protected function sumTimes($time1, $time2)
    {
        $times = [$time1, $time2];
        $minutes = 0;

        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60 + $minute;
        }

        $hours = floor($minutes / 60);
        $minutesLeft = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $minutesLeft);
    }

    /**
     * Calculate the total time from the timesheets.
     *
     * @param \Illuminate\Support\Collection $timesheets
     * @return string
     */
    protected function calculateTotalTime($timesheets)
    {
        $totalMinutes = 0;

        foreach ($timesheets as $timesheet) {
            $time = $timesheet->time;
            list($hours, $minutes) = explode(':', $time);
            $totalMinutes += $hours * 60 + $minutes;
        }

        $hours = floor($totalMinutes / 60);
        $minutesLeft = $totalMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutesLeft);
    }
}
