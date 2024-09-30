<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Project;
use App\Models\ProjectTimesheet;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    const HOURS_IN_A_DAY = 8;

    public $holidays;
    public $holiday;
    public $weekDates;
    public $checkInTimes = [];
    public $checkOutTimes = [];
    public $firstInTime;
    public $lastOutTime;
    public $attendance;
    public $newHires;
    public $timesheet;
    public $deviation = 0;
    public $workingDays = 0;
    public $weeklyTrainingHours = 0;
    public $monthlyTrainingHours = 0;
    public $weeklyProjectHours = 0;
    public $monthlyProjectHours = 0;
    public $lastWeekProjectHours = 0;
    public $lastMonthProjectHours;
    public $lastWeekTrainingHours = 0;
    public $lastMonthTrainingHours;
    public $weeklyBenchHours = 0;
    public $monthlyBenchHours = 0;
    public $lastWeekBenchHours = 0;
    public $lastMonthBenchHours;
    public $weeklyLearningHours = 0;
    public $monthlyLearningHours = 0;
    public $lastWeekLearningHours = 0;
    public $lastMonthLearningHours;
    public $weeklyLeaveDays = 0;
    public $monthlyLeaveDays = 0;
    public $lastWeekLeaveDays = 0;
    public $lastMonthLeaveDays;
    public $weeklyTotalHours;
    public $monthlyTotalHours;
    public $lastWeekTotalHours;
    public $lastMonthTotalHours;
    public $weeklyDeviation;
    public $monthlyDeviation;
    public $lastWeekDeviation;
    public $lastMonthDeviation;
    public $projects;





    public function mount()
    {
        // Get today's date
        $today = Carbon::now();

        // Fetch upcoming holidays (today and after), and order them by date
        $this->holidays = Holiday::where('date', '>=', $today)
            ->orderBy('date')
            ->get();

        // Get the start of the week (Sunday)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);

        // Generate an array of dates for the current week (Sunday to Saturday)
        $this->weekDates = collect();
        for ($i = 0; $i < 7; $i++) {
            $this->weekDates->push($startOfWeek->copy()->addDays($i));
        }

        // Get the authenticated employee ID
        $employeeId = auth()->guard('employee')->id();

        // Fetch attendance for today for the employee
        $this->attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->with('attendanceEntries')
            ->first();

        // Check if the attendance exists
        if ($this->attendance) {
            // Get attendance entries
            $attendanceEntries = $this->attendance->attendanceEntries->sortBy('entry_time');

            // Find first check-in and last check-out times
            $firstInEntry = $attendanceEntries->firstWhere('entry_type', 1); // 1 means check-in
            $lastOutEntry = $attendanceEntries->where('entry_type', 0)->sortByDesc('entry_time')->first(); // 0 means check-out

            // Set check-in and check-out times
            $this->firstInTime = $firstInEntry ? Carbon::parse($this->attendance->date . ' ' . $firstInEntry->entry_time) : null;
            $this->lastOutTime = $lastOutEntry ? Carbon::parse($this->attendance->date . ' ' . $lastOutEntry->entry_time) : null;

            // Separate check-in and check-out times
            foreach ($attendanceEntries as $entry) {
                if ($entry->entry_type == 1) {
                    $this->checkInTimes[] = $entry->entry_time;
                } elseif ($entry->entry_type == 0) {
                    $this->checkOutTimes[] = $entry->entry_time;
                }
            }
            $this->holiday = Holiday::all();

        }

        // Fetch new hires within the last 15 days
        $this->newHires = Employee::where('created_at', '>=', $today->subDays(15))
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch timesheets for the employee within the last 100 days
        $this->timesheet = ProjectTimesheet::where('employee_id', $employeeId)
            ->where('date', '>=', $today->subDays(7))
            ->with('project') // Eager loading project to prevent N+1 query issues
            ->orderBy('created_at', 'desc')
            ->get();
        // Fetch timesheets for the authenticated employee
        $timesheets = ProjectTimesheet::where('employee_id', $employeeId)->get();

        // Calculate working days and expected hours
        $this->workingDays = $this->calculateWorkingDays(now()->startOfMonth(), now());
        $expectedHours = $this->workingDays * 8;  // Assume 8 hours per working day

        // Calculate total project hours
        $totalTimeDecimal = $this->timesheet->sum(fn($timesheet) => $this->convertTimeToDecimal($timesheet->time));
        $this->deviation = $totalTimeDecimal - $expectedHours;

        $this->projects = Project::whereHas('allocations', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId);
        })
        ->orWhereHas('group', function ($query) {
            $query->whereNull('group_id');
        })
        ->get();
        

        // Consolidate weekly and monthly hours for training
        $this->consolidateHours($today);
    }
    // Consolidate weekly and monthly training hours
    protected function consolidateHours($today)
    {
        $today = Carbon::now(); // Get current date and time



        // Fetch timesheets for the current week (from Monday to today)
        $WeekTimesheets = ProjectTimesheet::where('employee_id', auth()->guard('employee')->id())
            ->where('date', '>=', $today->copy()->startOfWeek(Carbon::MONDAY)) // From the start of this week (Monday)
            ->where('date', '<=', $today) // Up to today
            ->get();

        // Fetch timesheets for the current month (from the 1st of this month to today)
        $MonthTimesheets = ProjectTimesheet::where('employee_id', auth()->guard('employee')->id())
            ->where('date', '>=', $today->copy()->startOfMonth()) // From the start of this month
            ->where('date', '<=', $today) // Up to today
            ->get();


        // Fetch timesheets for the last complete week (Sunday to Saturday)
        $lastWeekTimesheets = ProjectTimesheet::where('employee_id', auth()->guard('employee')->id())
            ->where('date', '>=', $today->copy()->subWeek()->startOfWeek(Carbon::SUNDAY)) // Start of the last week (Sunday)
            ->where('date', '<=', $today->copy()->subWeek()->endOfWeek(Carbon::SATURDAY)) // End of the last week (Saturday)
            ->get();



        // Fetch timesheets for the last complete month
        $lastMonthTimesheets = ProjectTimesheet::where('employee_id', auth()->guard('employee')->id())
            ->where('date', '>=', $today->copy()->subMonth()->startOfMonth()) // Start of the last month
            ->where('date', '<=', $today->copy()->subMonth()->endOfMonth())   // End of the last month
            ->get();




        // Consolidate weekly and monthly project hours
        $this->weeklyProjectHours = $WeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * (!$timesheet->project->group_id ? 1 : 0); // Project hours are for non-grouped projects
        });

        $this->lastWeekProjectHours = $lastWeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * (!$timesheet->project->group_id ? 1 : 0); // Project hours are for non-grouped projects
        });

        $this->monthlyProjectHours = $MonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * (!$timesheet->project->group_id ? 1 : 0);
        });

        $this->lastMonthProjectHours = $lastMonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * (!$timesheet->project->group_id ? 1 : 0); // Project hours are for non-grouped projects
        });


        // Consolidate weekly and monthly training hours
        $this->weeklyTrainingHours = $WeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 4 ? 1 : 0);
        });

        $this->monthlyTrainingHours = $MonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 4 ? 1 : 0);
        });

        $this->lastWeekTrainingHours = $lastWeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 4 ? 1 : 0);
        });

        $this->lastMonthTrainingHours = $lastMonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 4 ? 1 : 0);
        });

        // Consolidate weekly and monthly Bench hours
        $this->weeklyBenchHours = $WeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 2 ? 1 : 0);
        });

        $this->monthlyBenchHours = $MonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 2 ? 1 : 0);
        });

        $this->lastWeekBenchHours = $lastWeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 2 ? 1 : 0);
        });

        $this->lastMonthBenchHours = $lastMonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 2 ? 1 : 0);
        });

        // Consolidate weekly and monthly Learning hours
        $this->weeklyLearningHours = $WeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 6 ? 1 : 0);
        });

        $this->weeklyLearningHours = $MonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 6 ? 1 : 0);
        });

        $this->lastWeekLearningHours = $lastWeekTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 6 ? 1 : 0);
        });

        $this->lastMonthLearningHours = $lastMonthTimesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 6 ? 1 : 0);
        });

        $this->weeklyLeaveDays = number_format($this->calculateLeaveDays($WeekTimesheets));
        $this->monthlyLeaveDays = number_format($this->calculateLeaveDays($MonthTimesheets));
        $this->lastWeekLeaveDays = number_format($this->calculateLeaveDays($lastWeekTimesheets));
        $this->lastMonthLeaveDays = number_format($this->calculateLeaveDays($lastMonthTimesheets));

        $this->weeklyTotalHours = $this->calculateTotalHours($WeekTimesheets);
        $this->monthlyTotalHours = $this->calculateTotalHours($MonthTimesheets);
        $this->lastWeekTotalHours = $this->calculateTotalHours($lastWeekTimesheets);
        $this->lastMonthTotalHours = $this->calculateTotalHours($lastMonthTimesheets);

        // Calculate deviations based on total hours and expected hours for each period
        // Calculate deviations
        $this->weeklyDeviation = $this->calculateDeviation(
            $today->copy()->startOfWeek(Carbon::MONDAY),
            $today
        );

        $this->monthlyDeviation = $this->calculateDeviation(
            $today->copy()->startOfMonth(),
            $today
        );

        $this->lastWeekDeviation = $this->calculateDeviation(
            $today->copy()->subWeek()->startOfWeek(Carbon::SUNDAY),
            $today->copy()->subWeek()->endOfWeek(Carbon::SATURDAY)
        );

        $this->lastMonthDeviation = $this->calculateDeviation(
            $today->copy()->subMonth()->startOfMonth(),
            $today->copy()->subMonth()->endOfMonth()
        );
    }

    protected function calculateTotalHours($timesheets)
    {
        // Sum total hours for each category
        $projectHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * (!$timesheet->project->group_id ? 1 : 0);
        });

        $benchHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 2 ? 1 : 0);
        });

        $trainingHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 4 ? 1 : 0);
        });

        $learningHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 6 ? 1 : 0);
        });

        $leaveHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 3 ? 1 : 0);
        });

        // Return total logged hours for the period
        return $projectHours + $benchHours + $trainingHours + $learningHours + $leaveHours;
    }

    protected function calculateDeviation($startDate, $endDate)
    {
        // Calculate working days in the given date range
        $workingDays = $this->calculateWorkingDays($startDate, $endDate);
        $expectedHours = $workingDays * self::HOURS_IN_A_DAY;

        // Fetch timesheets for the given period
        $timesheets = ProjectTimesheet::where('employee_id', auth()->guard('employee')->id())
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get();

        // Calculate total logged hours
        $totalTimeDecimal = $timesheets->sum(fn($timesheet) => $this->convertTimeToDecimal($timesheet->time));

        // Calculate deviation
        return $totalTimeDecimal - $expectedHours;
    }

    // Convert time (HH:MM) to decimal
    protected function convertTimeToDecimal($time)
    {
        list($hours, $minutes) = explode(':', $time);
        return $hours + ($minutes / 60);
    }

    // Convert decimal time to HH:MM format
    protected function convertDecimalToTime($decimal)
    {
        $hours = floor($decimal);
        $minutes = ($decimal - $hours) * 60;
        return sprintf('%02d:%02d', $hours, abs($minutes));
    }

    // Calculate working days between two dates
    protected function calculateWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            if (!$currentDate->isWeekend()) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }
    protected function calculateLeaveDays($timesheets)
    {
        // Sum total leave hours for group_id == 3
        $totalLeaveHours = $timesheets->sum(function ($timesheet) {
            return $this->convertTimeToDecimal($timesheet->time) * ($timesheet->project->group_id == 3 ? 1 : 0);
        });

        // Convert total leave hours to leave days by dividing by the constant
        return $totalLeaveHours / self::HOURS_IN_A_DAY; // Use self:: to refer to the constant
    }
    public function render()
    {

        return view('livewire.dashboard', [
            'attendance' => $this->attendance,
            'firstInTime' => $this->firstInTime,
            'lastOutTime' => $this->lastOutTime,
            'projects' => $this->projects,
            'holidays' => $this->holidays,
            'weekDates' => $this->weekDates,
            'checkInTimes' => $this->checkInTimes,
            'checkOutTimes' => $this->checkOutTimes,
            'employees' => $this->newHires,
            'timesheets' => $this->timesheet,
            'deviation' => $this->convertDecimalToTime($this->deviation),
        ]);
    }
}
