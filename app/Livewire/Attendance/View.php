<?php

namespace App\Livewire\Attendance;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\AttendanceEntry;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

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
    public $showModal = false;
    public $selectedAttendanceId;
    public $newStatus;
    public $reportingEmployees = [];
    public $selectedEmployee = null;


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

        // Map the period dates to attendance data
        $this->attendanceData = $periodDates->map(function ($date) use ($attendances, $holidays, &$totalSeconds, &$totalPayableSeconds, &$totalHolidaySeconds, &$totalWeekendSeconds, &$totalPresentSeconds, &$totalAbsentSeconds) {
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

            $attendances = Attendance::with('employee')
            ->when($this->selectedEmployee, function ($query) {
                $query->where('employee_id', $this->selectedEmployee);
            })
            ->get();
        
             // Fetch employee_id and employee_name directly from attendance
    $employeeId = $attendance ? $attendance->employee_id : '-';
    $employeeName = $attendance && $attendance->employee ? $attendance->employee->name : '-';

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
            $attendanceEntries = AttendanceEntry::where('attendance_id', $attendanceId)
                ->orderBy('entry_time')
                ->get();

            $checkInTimes = [];
            $checkOutTimes = [];

            foreach ($attendanceEntries as $entry) {
                if ($entry->entry_type == 1) {
                    $checkInTimes[] = $entry->entry_time;
                } elseif ($entry->entry_type == 0) {
                    $checkOutTimes[] = $entry->entry_time;
                }
            }

            $forenoonHours = gmdate('H:i', $forenoonSeconds);
            $afternoonHours = gmdate('H:i', $afternoonSeconds);

            $dayName = Carbon::parse($date)->format('l');
            $isWeekend = in_array($dayName, ['Saturday', 'Sunday']);

            $status = '-';
            $payableHours = 0;

            if ($attendance) {
                // Calculate the status based on attendance time
                if ($attendance) {
                    // Automatic status calculation
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

                    // Update time calculations based on the status
                    if ($status === 'pp') {
                        $totalPresentSeconds += $daySeconds;
                    } elseif ($status === 'aa') {
                        $totalAbsentSeconds += 8 * 3600; // Add 8 hours for a full day absence
                    } elseif ($status == 'ap' || $status == 'pa') {
                        $totalAbsentSeconds += 4 * 3600; // Add 4 hours for half-day absence
                    }
                } else {
                    // Manual status handling
                    $status = $attendance->status; // Use the manual status
                    $this->adjustHoursBasedOnManualStatus($status, $totalPresentSeconds, $totalAbsentSeconds);
                }

                // Calculate payable hours
                $payableHours = $this->calculatePayableHours($status);

                // Save payable hours and status in the database
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

                $totalPayableSeconds += $payableSeconds;
            } else {
                $payableHours = '-';
            }
            $attendanceData = Attendance::select('employee_id')->get();



            return [
                'attendanceId' => $attendance ? $attendance->id : null,
                'employee_name' => $employeeName,
                'date' => $date,
                'firstInTime' => $firstInTime,
                'lastOutTime' => $lastOutTime,
                'totalHours' => $dayHours,
                'payableHours' => $payableHours,
                'isWeekend' => $isWeekend,
                'status' => $formattedStatus,
                'holiday' => $holiday ? $holiday->reason : null,
                'check_in_times' => $checkInTimes,
                'check_out_times' => $checkOutTimes,

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
    private function adjustHoursBasedOnManualStatus($status, &$totalPresentSeconds, &$totalAbsentSeconds)
    {
        switch ($status) {
            case 'pp':
                // Full day present, add 8 hours to present time
                $totalPresentSeconds += 8 * 3600;
                break;
            case 'pa':
                // Morning present, afternoon absent, add 4 hours to present and 4 hours to absent
                $totalPresentSeconds += 4 * 3600;
                $totalAbsentSeconds += 4 * 3600;
                break;
            case 'ap':
                // Morning absent, afternoon present, add 4 hours to present and 4 hours to absent
                $totalPresentSeconds += 4 * 3600;
                $totalAbsentSeconds += 4 * 3600;
                break;
            case 'aa':
                // Full day absent, add 8 hours to absent time
                $totalAbsentSeconds += 8 * 3600;
                break;
            default:
                // If the status is undefined, no hours are added
                break;
        }
    }

    private function calculatePayableHours($status)
    {
        switch ($status) {
            case 'pp':
                return 8 * 3600; // 8 hours payable for full day present
            case 'pa':
            case 'ap':
                return 4 * 3600; // 4 hours payable for half day present
            case 'aa':
                return 0; // No payable hours for full day absent
            default:
                return 0;
        }
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

    public function fetchReportingEmployees()
    {
        $employee = auth()->guard('employee')->user();
        $this->reportingEmployees = Employee::where('reporting_manager', $employee->id)->get();
    }

    public function showStatusModal($attendanceId)
    {
        $this->selectedAttendanceId = $attendanceId;
        $this->showModal = true;

        // Trigger the modal display
        $this->dispatchBrowserEvent('showModal');
    }

    public function updateStatus($attendanceId, $newStatus)
    {
        $attendance = Attendance::find($attendanceId);

        if ($attendance) {
            $attendance->status = $newStatus;
            $attendance->is_manual = true;
            $attendance->save();

            $this->loadAttendanceData();
            $this->closeModal(); // Close the modal after saving
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedAttendanceId', 'newStatus']);
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

        return view('livewire.attendance.view', [
            'reportingEmployees' => $this->reportingEmployees,
            'attendanceData' => $this->attendanceData,
        ]);
    }
    public function exportToExcel()
    {
        // Check if a specific employee is selected
        $selectedEmployee = $this->selectedEmployee ?: Auth::guard('employee')->id();
        $viewMode=$this->viewMode;
        $weekOffset=$this->weekOffset;
        $monthOffset=$this->monthOffset;

        // Pass the relevant data to the export class
        return Excel::download(new AttendanceExport( $selectedEmployee,$viewMode, $weekOffset, $monthOffset), 'attendance.xlsx');
    }

    public function checkAttendance()
    {
        $attendance = Attendance::all();
        if (!$attendance) {
            Session::flash('error', 'No attendance record available to edit.');
            return redirect()->back();
        }
    }
}
