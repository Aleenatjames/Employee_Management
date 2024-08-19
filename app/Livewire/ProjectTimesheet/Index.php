<?php

namespace App\Livewire\ProjectTimesheet;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectTimesheet;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $startDate;
    public $endDate;
    public $selectedProject = '';
    public $selectedEmployee;

    protected $listeners = ['delete'];

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

    public function delete($id)
    {
        ProjectTimesheet::findOrFail($id)->delete();
        session()->flash('message', 'Timesheet deleted successfully.');
    }

    public function render()
    {
        $employeeId = Auth::guard('employee')->id();
        $currentDate = now()->format('Y-m-d');
        $startDate = $this->startDate ?: '2024-08-08'; // Default start date if not provided
        $endDate = $this->endDate ?: $currentDate; // Default end date if not provided
        $workingHoursPerDay = 8; // Assuming 8 hours is the standard working hours per day

        // Determine the target employee ID
        $targetEmployeeId = $this->selectedEmployee ?? $employeeId;

        // Fetch timesheets based on the applied filters and sorting
        $timesheetsQuery = ProjectTimesheet::query()
            ->with(['project'])
            ->where('employee_id', $targetEmployeeId)
            ->when($this->startDate, function ($query) {
                $query->where('date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->where('date', '<=', $this->endDate);
            })
            ->when($this->selectedProject, function ($query) {
                $query->where('project_id', $this->selectedProject);
            })
            ->where(function ($query) {
                $query->where('comment', 'like', '%' . $this->search . '%')
                    ->orWhere('taskid', 'like', '%' . $this->search . '%') // Search by task_id
                    ->orWhereHas('project', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Paginate the query
        $timesheets = $timesheetsQuery->paginate($this->perPage);

        // Calculate the total logged time by summing up the times
        $loggedTime = '00:00';
        foreach ($timesheets as $timesheet) {
            $loggedTime = $this->sumTimes($loggedTime, $timesheet->time);
        }

        // Calculate the number of working days between startDate and endDate excluding holidays
        $totalWorkingDays = $this->calculateWorkingDays($startDate, $endDate);
        $availableTimeInDecimal = $totalWorkingDays * $workingHoursPerDay;

        // Calculate deviation
        $loggedTimeInDecimal = $this->convertTimeToDecimal($loggedTime);
        $deviationInDecimal = $loggedTimeInDecimal - $availableTimeInDecimal;
        $deviation = $this->convertDecimalToTime($deviationInDecimal);

        // Convert available time back to time format
        $availableTime = $this->convertDecimalToTime($availableTimeInDecimal);

        // Fetch projects allocated to the authenticated employee
        $projects = Project::whereHas('allocations', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId);
        })
        ->orWhereHas('group', function ($query) {
            $query->whereNotNull('group_id'); // Ensure the project has a group_id
        })
        ->get();

        // Fetch reporting employees if the current user is a manager
        $reportingEmployees = Employee::where('reporting_manager', $employeeId)->get();

        // Pass the timesheets, projects, and calculated times to the view
        return view('livewire.project-timesheet.index', [
            'timesheets' => $timesheets,
            'projects' => $projects,
            'loggedTime' => $loggedTime,
            'deviation' => $deviation,
            'availableTime' => $availableTime,
            'reportingEmployees' => $reportingEmployees,
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

    /**
     * Convert decimal hours to time format (HH:MM).
     *
     * @param float $decimal
     * @return string
     */
    protected function convertDecimalToTime($decimal)
    {
        $hours = floor($decimal);
        $minutes = ($decimal - $hours) * 60;
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
    
}
