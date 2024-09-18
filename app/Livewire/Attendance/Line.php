<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceEntry;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Livewire\Component;

class Line extends Component
{
    public $weekOffset = 0;
    public $monthOffset = 0;
    public $viewMode = 'weekly'; // Default view mode
    public $attendanceData = [];
    public $selectedEmployee = null;
    public $reportingEmployees = [];
    public $totalHoursInSeconds;
    public $payableHours = 0;
    public $totalPayableHoursInSeconds;
    public $totalHolidayHours = 0;
    public $totalPresentHours = 0;
    public $totalWeekendHours = 0;
    public $totalAbsentHours = 0;
    public $totalPayableHours;
    public $totalHours;
    public $totalHolidayDays = 0;
    public $totalPresentDays = 0;
    public $totalWeekendDays = 0;
    public $totalAbsentDays = 0;
    public $totalPayableDays;
    public $totalDays;

    public function mount()
    {
        $this->selectedEmployee = auth()->guard('employee')->user()->id;
        $this->loadAttendanceData();
        $this->fetchReportingEmployees();
    }
    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->weekOffset = 0; // Reset week offset when changing view mode
        $this->monthOffset = 0; // Reset month offset when changing view mode
        $this->loadAttendanceData();
    }

    public function previousPeriod()
    {
        if ($this->viewMode === 'weekly') {
            $this->weekOffset--;
        } else {
            $this->monthOffset--;
        }
        $this->loadAttendanceData();
    }

    public function nextPeriod()
    {
        if ($this->viewMode === 'weekly') {
            $this->weekOffset++;
        } else {
            $this->monthOffset++;
        }

        $this->loadAttendanceData();
    }

    public function updatedSelectedEmployee()
    {
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $employee = Employee::find($this->selectedEmployee);
    
        if (!$employee) {
            // Handle case where employee is not found
            $this->attendanceData = [];
            return;
        }
    
        $employeeId = $employee->id;
        $employeeCreationDate = Carbon::parse($employee->created_at)->format('Y-m-d'); // Format the employee creation date
        $today = Carbon::now()->format('Y-m-d'); // Get today's date
    
        if ($this->viewMode === 'weekly') {
            // Start the week on Sunday and end on Saturday
            $startOfPeriod = Carbon::now()->startOfWeek(Carbon::SUNDAY)->addWeeks($this->weekOffset)->format('Y-m-d');
            $endOfPeriod = Carbon::now()->endOfWeek(Carbon::SATURDAY)->addWeeks($this->weekOffset)->format('Y-m-d');
        } else {
            $startOfPeriod = Carbon::now()->startOfMonth()->addMonths($this->monthOffset)->format('Y-m-d');
            $endOfPeriod = Carbon::now()->endOfMonth()->addMonths($this->monthOffset)->format('Y-m-d');
        }
    
        // Ensure the start of the period is not before the employee creation date
        if ($startOfPeriod < $employeeCreationDate) {
            $startOfPeriod = $employeeCreationDate; // Adjust to the employee's creation date if necessary
        }
    
       
        // Generate period dates
        $periodDates = collect();
        for ($date = Carbon::parse($startOfPeriod); $date->lte($endOfPeriod); $date->addDay()) {
            $periodDates->push($date->format('Y-m-d'));
        }
    
        // Fetch reporting employees
        $reportingEmployees = Employee::where('reporting_manager', $employeeId)->pluck('id')->toArray();
    
        if ($this->selectedEmployee == 'all') {
            $this->attendanceData = Attendance::all();
        } elseif ($this->selectedEmployee) {
            $this->attendanceData = Attendance::where('employee_id', $this->selectedEmployee)->get();
        } else {
            $this->attendanceData = [];
        }
    
        // Fetch holidays and attendances
        $holidays = Holiday::whereBetween('date', [$startOfPeriod, $endOfPeriod])->get();
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startOfPeriod, $endOfPeriod])
            ->with('attendanceEntries')
            ->get();
    
        // Initialize total counters
        $totalSeconds = 0;
        $totalPayableSeconds = 0;
        $totalHolidaySeconds = 0;
        $totalWeekendSeconds = 0;
        $totalPresentSeconds = 0;
        $totalAbsentSeconds = 0;
    
        $this->attendanceData = $periodDates->map(function ($date) use ($attendances, $holidays, $employeeCreationDate, &$totalSeconds, &$totalPayableSeconds, &$totalHolidaySeconds, &$totalWeekendSeconds, &$totalPresentSeconds, &$totalAbsentSeconds) {
            $attendance = $attendances->firstWhere('date', $date);
            $attendanceId = $attendance ? $attendance->id : null;
        
            $firstInEntry = $attendance ? $attendance->attendanceEntries->firstWhere('entry_type', 1) : null;
            $lastOutEntry = $attendance ? $attendance->attendanceEntries->where('entry_type', 0)->sortByDesc('entry_time')->first() : null;
        
            $firstInTime = $firstInEntry ? Carbon::parse($attendance->date . ' ' . $firstInEntry->entry_time) : null;
            $lastOutTime = $lastOutEntry ? Carbon::parse($attendance->date . ' ' . $lastOutEntry->entry_time) : null;
        
            $daySeconds = $attendance ? $attendance->attendanceEntries->sum('duration') : 0;
            $dayHours = $daySeconds > 0 ? gmdate('H:i', $daySeconds) : '-';
    
            // Update total hours in seconds
            $totalSeconds += $daySeconds;
        
            $attendanceEntries = AttendanceEntry::where('attendance_id', $attendanceId)
                ->orderBy('entry_time')
                ->get();
        
            $checkInTimes = [];
            $checkOutTimes = [];
        
            $forenoonSeconds = 0;
            $afternoonSeconds = 0;
        
            $forenoonHours = gmdate('H:i', $forenoonSeconds);
            $afternoonHours = gmdate('H:i', $afternoonSeconds);
        
            $dayName = Carbon::parse($date)->format('l');
            $isWeekend = in_array($dayName, ['Saturday', 'Sunday']);
        
            $status = 'aa'; // Default to Absent
        
            // Calculate attendance forenoon and afternoon (4-hour split logic)
            $forenoonSeconds = $attendance ? $attendance->attendanceEntries->where('entry_type', 1)->sum('duration') : 0;
            $afternoonSeconds = $attendance ? $attendance->attendanceEntries->where('entry_type', 0)->sum('duration') : 0;
        
          // Get today's date
$today = Carbon::today();

if ($attendance) {
    // Check if the attendance date is today
    if ($attendance->date == $today) {
        // Save status as 'na' for today's date
        $status = 'na';
    } else {
        // Automatic status calculation for other dates
        if ($daySeconds >= 8 * 3600) {
            $status = 'pp'; // Present
        } else {
            if ($forenoonSeconds >= 4 * 3600 && $afternoonSeconds >= 4 * 3600) {
                $status = 'pp'; // Present
            } elseif ($forenoonSeconds >= 4 * 3600 && $afternoonSeconds < 4 * 3600) {
                $status = 'pa'; // Partial (Present/Absent)
            } elseif ($forenoonSeconds < 4 * 3600 && $afternoonSeconds >= 4 * 3600) {
                $status = 'ap'; // Absent (Present)
            } elseif ($forenoonSeconds < 4 * 3600 && $afternoonSeconds < 4 * 3600) {
                $status = 'aa'; // Absent (All Day)
            }
        }
    }

    // Save the final status to the database
    $attendance->status = $status;
    $attendance->save();
}
            foreach ($attendanceEntries as $entry) {
                if ($entry->entry_type == 1) {
                    $checkInTimes[] = $entry->entry_time;
                } elseif ($entry->entry_type == 0) {
                    $checkOutTimes[] = $entry->entry_time;
                }
            }
        
            // Check if the date is a holiday
            $holiday = $holidays->firstWhere('date', $date);
            if ($holiday) {
                $status = $holiday->type === 'restricted' ? 'Restricted Holiday' : 'Public Holiday';
                $payableHours = '08:00'; // Set payable hours to '8:00' on holidays
                $holidaySeconds = 8 * 3600;
                $totalHolidaySeconds += $holidaySeconds;
            } else {
                if ($isWeekend) {
                    $payableHours = '08:00'; // Full workday on weekends
                } else {
                    if ($daySeconds >= 14400) { // 4 hours or more
                        $payableHours = $daySeconds >= 28800 ? '08:00' : '04:00'; // Full day or 4 hours
                    } else {
                        $payableHours = '00:00'; // Less than 4 hours
                    }
                }
            }
        
            // Add weekend hours
            if ($isWeekend) {
                $totalWeekendSeconds += 8 * 3600; // 8 hours for weekend
            }
        
            // Convert payableHours to seconds and update total payable time
            if ($payableHours !== '-') {
                list($hours, $minutes) = explode(':', $payableHours);
                $payableSeconds = ($hours * 3600) + ($minutes * 60);
        
                $totalPayableSeconds += $payableSeconds;
            } else {
                $payableHours = '-';
            }
        
            // Return the attendance data for the day, including 'payableHours'
            return [
                'date' => $date,
                'firstInTime' => $firstInTime,
                'lastOutTime' => $lastOutTime,
                'attendance' => $attendance ? $attendance->attendanceEntries : null,
                'totalHours' => $dayHours,
                'check_in_times' => $checkInTimes,
                'check_out_times' => $checkOutTimes,
                'payableHours' => $payableHours, // Include payableHours in the return array
                'status' => $status,
                'employeeCreationDate' => $employeeCreationDate,
                'holiday'=>$holiday,
            ];
        })->toArray();
        

        // Calculate total hours for the visible page
        $this->totalHoursInSeconds = array_sum(array_map(function ($entry) {
            if ($entry['totalHours'] !== '-') {
                list($hours, $minutes) = explode(':', $entry['totalHours']);
                return ($hours * 3600) + ($minutes * 60);
            }
            return 0;
        }, $this->attendanceData));

        $this->totalPayableHoursInSeconds = array_sum(array_map(function ($entry) {
            if ($entry['payableHours'] !== '-') {
                list($hours, $minutes) = explode(':', $entry['payableHours']);
                return ($hours * 3600) + ($minutes * 60);
            }
            return 0;
        }, $this->attendanceData));

        // Calculate and format total hours and days
        $this->totalHours = $this->formatHours($totalSeconds);
        $this->totalPayableHours = $this->formatHours($totalPayableSeconds);
        $this->totalHolidayHours = $this->formatHours($totalHolidaySeconds);
        $this->totalWeekendHours = $this->formatHours($totalWeekendSeconds);
        $this->totalPresentHours = $this->formatHours($totalPresentSeconds);
        $this->totalAbsentHours = $this->formatHours($totalAbsentSeconds);
        // Calculate and format total hours and days
        $this->totalDays = $this->formatDays($totalSeconds);
        $this->totalPayableDays = $this->formatDays($totalPayableSeconds);
        $this->totalHolidayDays = $this->formatDays($totalHolidaySeconds);
        $this->totalWeekendDays = $this->formatDays($totalWeekendSeconds);
        $this->totalPresentDays = $this->formatDays($totalPresentSeconds);
        $this->totalAbsentDays = $this->formatDays($totalAbsentSeconds);
    }

    public function fetchReportingEmployees()
    {
        $employee = auth()->guard('employee')->user();
        $this->reportingEmployees = Employee::where('reporting_manager', $employee->id)->get();
    }
    private function convertToHours($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = ($seconds % 3600) / 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    private function convertToDays($seconds)
    {
        $hoursPerDay = 8; // 8 hours per workday
        $hoursWorked = $seconds / 3600; // Convert seconds to hours
        $days = $hoursWorked / $hoursPerDay;

        return round($days, 1); // Round to 1 decimal place for accuracy (e.g., 0.5 days)
    }
    // Formats seconds into days and hours
    private function formatHours($seconds)
    {

        $hoursAndMinutes = $this->convertToHours($seconds);

        return sprintf('%s hrs', $hoursAndMinutes);
    }
    private function formatDays($seconds)
    {
        $days = $this->convertToDays($seconds);


        return sprintf('%.1f day(s)', $days);
    }

    public function render()
    {
        return view('livewire.attendance.line');
    }
}
