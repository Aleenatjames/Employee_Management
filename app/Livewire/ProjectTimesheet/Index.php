<?php

namespace App\Livewire\ProjectTimesheet;

use App\Exports\TimesheetsExport;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\ProjectTimesheet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    #[Url()]
    public $search = '';
    #[Url()]
    public $perPage = 10;
    #[Url()]
    public $sortField = 'date';
    #[Url()]
    public $sortDirection = 'desc';
    #[Url()]
    public $startDate;
    #[Url()]
    public $endDate;
    #[Url()]
    public $selectedProject = '';
    #[Url()]
    public $selectedEmployee;
    public $isManagerView;
    public $taskSearch;


    protected $listeners = ['delete'];
    public $selectedEmployees = []; // Default to an empty array

    public function mount()
    {
        // If authenticated employee is viewing their own timesheet
        if (Auth::check()) {
            $this->selectedEmployees = [Auth::id()]; // Default to the authenticated employee
        }
    }

    public function updatedSelectedEmployees($value)
    {
        if (in_array('', $this->selectedEmployees)) {
            // If 'All' is selected, reset the array to include all employees
            $this->selectedEmployees = []; // or you can use Employee::all()->pluck('id') if needed
        }
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

    public function delete($id)
    {
        ProjectTimesheet::findOrFail($id)->delete();
        session()->flash('message', 'Timesheet deleted successfully.');
    }

    public function render()
    {
        $employeeId = Auth::guard('employee')->id();
        $currentDate = now()->format('Y-m-d');

        // Determine the target employee ID(s)
        if ($this->selectedEmployee === '' || $this->selectedEmployee === 'all') {
            $targetEmployeeIds = Employee::where('reporting_manager', $employeeId)->pluck('id')->toArray();
        } else {
            $targetEmployeeIds = [$this->selectedEmployee ?? $employeeId];
        }

        // Fetch the earliest created_at date among the target employees
        $employeeCreatedAt = Employee::whereIn('id', $targetEmployeeIds)
            ->min('created_at');

        // Check if $employeeCreatedAt is not null and format it, otherwise use current date
        $employeeCreatedAt = $employeeCreatedAt ? Carbon::parse($employeeCreatedAt)->format('Y-m-d') : $currentDate;

        // Set the start date to the earliest employee's created_at date if not provided
        $startDate = $this->startDate ?: $employeeCreatedAt;
        $endDate = $this->endDate ?: $currentDate;
        $workingHoursPerDay = 8;

        // Fetch all timesheets based on the applied filters (no pagination)
        $allTimesheetsQuery = ProjectTimesheet::query()
            ->whereIn('employee_id', $targetEmployeeIds)
            ->when($this->startDate, function ($query) {
                $query->where('date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->where('date', '<=', $this->endDate);
            })
            ->when($this->selectedProject, function ($query) {
                $query->where('project_id', $this->selectedProject);
            })
            ->when($this->taskSearch, function ($query) {
                $query->where('taskid', 'like', '%' . $this->taskSearch . '%'); // TaskId filtering
            })
            ->where(function ($query) {
                $query->where('comment', 'like', '%' . $this->search . '%')
                    ->orWhereHas('project', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });

        // Clone the query to use it for pagination
        $paginatedTimesheetsQuery = clone $allTimesheetsQuery;

        // Fetch the paginated timesheets
        $timesheets = $paginatedTimesheetsQuery->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Fetch all timesheets (without pagination) to calculate the total logged time
        $allTimesheets = $allTimesheetsQuery->get();

        // Calculate the total logged time
        $loggedTime = '00:00';
        foreach ($allTimesheets as $timesheet) {
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
    public function exportToExcel()
    {
        // Check if a specific employee is selected
        $selectedEmployee = $this->selectedEmployee ?: Auth::guard('employee')->id();

        $startDate = $this->startDate; // Ensure these properties exist and are populated correctly in your component
        $endDate = $this->endDate;
        $selectedProject = $this->selectedProject;

        // Pass the relevant data to the export class
        return Excel::download(new TimesheetsExport($startDate, $endDate, $selectedProject, $selectedEmployee), 'timesheets.xlsx');
    }
}
