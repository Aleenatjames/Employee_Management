<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceEntry;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class View extends Component
{
    public $weekOffset = 0;
    public $viewMode = 'weekly'; // Default view mode
    public $monthOffset = 0;
    public $attendanceData = [];
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
        $this->loadAttendanceData();
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
    public function loadAttendanceData()
    {
        $employee = auth()->guard('employee')->user();
        $employeeId = $employee->id;
        $employeeCreationDate = $employee->created_at->format('Y-m-d');

        // Determine start and end of the period based on the view mode
        if ($this->viewMode === 'weekly') {
            $startOfPeriod = Carbon::now()->startOfWeek()->addWeeks($this->weekOffset)->format('Y-m-d');
            $endOfPeriod = Carbon::now()->endOfWeek()->addWeeks($this->weekOffset)->format('Y-m-d');
        } else {
            $startOfPeriod = Carbon::now()->startOfMonth()->addMonths($this->monthOffset)->format('Y-m-d');
            $endOfPeriod = Carbon::now()->endOfMonth()->addMonths($this->monthOffset)->format('Y-m-d');
        }

        if ($startOfPeriod < $employeeCreationDate) {
            $startOfPeriod = $employeeCreationDate;
        }

        // Generate period dates
        $periodDates = collect();
        for ($date = Carbon::parse($startOfPeriod); $date->lte($endOfPeriod); $date->addDay()) {
            $periodDates->push($date->format('Y-m-d'));
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
        $totalAbsentSeconds = 0; // New counter for total absent ('aa') hours

        // Map the period dates to attendance data
        $this->attendanceData = $periodDates->map(function ($date) use ($attendances, $holidays, &$totalSeconds, &$totalPayableSeconds, &$totalHolidaySeconds, &$totalWeekendSeconds, &$totalPresentSeconds, &$totalAbsentSeconds) {
            $attendance = $attendances->firstWhere('date', $date);
            $firstInEntry = $attendance ? $attendance->attendanceEntries->firstWhere('entry_type', 1) : null;
            $lastOutEntry = $attendance ? $attendance->attendanceEntries->where('entry_type', 0)->sortByDesc('entry_time')->first() : null;

            $firstInTime = $firstInEntry ? Carbon::parse($attendance->date . ' ' . $firstInEntry->entry_time) : null;
            $lastOutTime = $lastOutEntry ? Carbon::parse($attendance->date . ' ' . $lastOutEntry->entry_time) : null;

            $daySeconds = $attendance ? $attendance->attendanceEntries->sum('duration') : 0;
            $dayHours = gmdate('H:i', $daySeconds);

            // Update total hours in seconds
            $totalSeconds += $daySeconds;

            $forenoonSeconds = 0;
            $afternoonSeconds = 0;

            if ($attendance) {
                foreach ($attendance->attendanceEntries as $entry) {
                    $entryTime = Carbon::parse($attendance->date . ' ' . $entry->entry_time);
                    if ($entryTime->hour < 12) {
                        $forenoonSeconds += $entry->duration;
                    } else {
                        $afternoonSeconds += $entry->duration;
                    }
                }
            }

            $forenoonHours = gmdate('H:i', $forenoonSeconds);
            $afternoonHours = gmdate('H:i', $afternoonSeconds);

            $dayName = Carbon::parse($date)->format('l');
            $isWeekend = in_array($dayName, ['Saturday', 'Sunday']);

            $status = '-';
            if ($attendance) {
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

                if ($status === 'pp') {
                    $totalPresentSeconds += $daySeconds;
                }

                if ($status === 'aa') {
                    $totalAbsentSeconds += 8 * 3600; // Add 8 hours for a full day absence

                }

                if (!$isWeekend && $status == 'ap' || $status == 'pa') {
                    $totalAbsentSeconds += 4 * 3600;
                }
            }

            // Update the status in the database
            if ($attendance) {
                $attendance->status = $status;
                $attendance->save();
            }

            // Check if the date is a holiday
            $holiday = $holidays->firstWhere('date', $date);
            if ($holiday) {
                $status = $holiday->type === 'restricted' ? 'Restricted Holiday' : 'Public Holiday';
                $formattedStatus = $holiday->reason; // Add the holiday reason
                $payableHours = '08:00'; // Set payable hours to '8:00' on holidays
                // Update total holiday hours in seconds
                $holidaySeconds = 8 * 3600; // 8 hours of holiday
                $totalHolidaySeconds += $holidaySeconds;
            } else {
                $formattedStatus = $this->getFormattedStatus($status);
                // Adjust payable hours for weekends and general cases
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

            // Add weekend status
            if ($isWeekend) {
                $formattedStatus .= $formattedStatus !== '-' ? ', Weekend' : 'Weekend';
                $totalWeekendSeconds += 8 * 3600; // 8 hours for weekend
            }

            // Convert payableHours to seconds, handling the '-' case
            if ($payableHours !== '-') {
                list($hours, $minutes) = explode(':', $payableHours);
                $payableSeconds = ($hours * 3600) + ($minutes * 60);

                // Adjust payable hours based on the total seconds
                if ($payableSeconds >= 28800) {
                    $payableHours = '08:00';
                    $payableSeconds = 28800; // Cap at 8 hours
                } elseif ($payableSeconds == 14400) {
                    $payableHours = '04:00';
                    $payableSeconds = 14400; // Cap at 4 hours

                } else {
                    $payableHours = '00:00';
                    $payableSeconds = 0; // Default to 0 if less than 4 hours
                }

                // If the payable hours end up as '00:00', set it to '-'
                if ($payableHours === '00:00') {
                    $payableHours = '-';
                    $payableSeconds = 0;
                }

                // Update total payable hours in seconds
                $totalPayableSeconds += $payableSeconds;
            } else {
                // If the payableHours is '-', it should be treated as 0 seconds
                $payableSeconds = 0;
            }

            return [
                'date' => $date,
                'firstInTime' => $firstInTime,
                'lastOutTime' => $lastOutTime,
                'totalHours' => $dayHours,
                'payableHours' => $payableHours,
                'isWeekend' => $isWeekend,
                'status' => $formattedStatus,
                'holiday' => $holiday ? $holiday->reason : null,
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

        return sprintf('%s hours', $hoursAndMinutes);
    }
    private function formatDays($seconds)
    {
        $days = $this->convertToDays($seconds);


        return sprintf('%.1f days', $days);
    }

    private function getFormattedStatus($status)
    {
        switch ($status) {
            case 'pp':
                return 'Present';
            case 'pa':
                return 'Present, Absent';
            case 'ap':
                return 'Absent, Present';
            case 'aa':
                return 'Absent';
            default:
                return '-';
        }
    }

    public function previousWeek()
    {
        $this->weekOffset--;
        $this->loadAttendanceData();
    }

    public function nextWeek()
    {
        $this->weekOffset++;
        $this->loadAttendanceData();
    }

    public function render()
    {
        return view('livewire.attendance.view');
    }
}
