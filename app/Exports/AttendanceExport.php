<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{

    protected $selectedEmployee;
    protected $viewMode;
    protected $weekOffset;
    protected $monthOffset;

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct($selectedEmployee = null, $viewMode, $weekOffset, $monthOffset)
    {
        $this->selectedEmployee = $selectedEmployee;
        $this->viewMode = $viewMode;
        $this->weekOffset = $weekOffset;
        $this->monthOffset = $monthOffset;
    }
    public function collection()
    {
        if ($this->viewMode === 'weekly') {
            $startDate = Carbon::now()->startOfWeek()->addWeeks($this->weekOffset);
            $endDate = Carbon::now()->endOfWeek()->addWeeks($this->weekOffset);
        } elseif ($this->viewMode === 'monthly') {
            $startDate = Carbon::now()->startOfMonth()->addMonths($this->monthOffset);
            $endDate = Carbon::now()->endOfMonth()->addMonths($this->monthOffset);
        } else {
            throw new \Exception('Invalid view mode specified.');
        }

        // Fetch attendance records within the calculated date range
        $attendances = Attendance::query()
            ->when($this->selectedEmployee !== 'all', function ($query) {
                $query->where('employee_id', $this->selectedEmployee);
            })
            ->whereBetween('attendance.date', [$startDate->toDateString(), $endDate->toDateString()])
            ->with(['attendanceEntries' => function ($query) {
                $query->orderBy('entry_time');
            }, 'employee'])
            ->leftJoin('holidays', 'attendance.date', '=', 'holidays.date')
            ->select('attendance.*', 'holidays.reason as holiday_reason')
            ->get();

        // Generate the full date range including dates with no attendance
        $dates = $this->generateDateRange($startDate, $endDate);

        // Prepare a mapping of dates to attendance records
        $attendanceMap = $attendances->keyBy('date');

        // Generate data including all dates
        $data = [];
        foreach ($dates as $date) {
            $data[] = [
                'date' => $date,
                'attendance' => $attendanceMap->get($date, null)
            ];
        }

        return collect($data);
    }


    public function headings(): array
    {
        return [
            'Date',
            'Employee_Name',
            'First In',
            'Last Out',
            'Total Hours',
            'Payable Hours',
            'Status'
        ];
    }
    public function map($data): array
    {
        $attendance = $data['attendance'];
        $date = $data['date'];

        // Default values
        $employeeName = '';
        $firstInTime = '';
        $lastOutTime = '';
        $totalHours = '';
        $payableHours = '';
        $status = '-';

        if ($attendance) {
            $employeeName = $attendance->employee ? $attendance->employee->name : 'No Employee';
            $firstInTime = $attendance->attendanceEntries->where('entry_type', 1)->first()?->entry_time ?? '-';
            $lastOutTime = $attendance->attendanceEntries->where('entry_type', 0)->last()?->entry_time ?? '-';
            $totalDuration = $attendance->attendanceEntries->sum('duration');
            $totalHours = gmdate('H:i', $totalDuration);
            $payableHours = $this->calculatePayableHours($totalDuration, $date);
            $status = $this->statusFormation($date, $attendance->status, $attendance->holiday_reason);
        } else {
            $status = $this->statusFormation($date, null, null);
        }

        return [
            $date,
            $employeeName,
            $firstInTime,
            $lastOutTime,
            $totalHours,
            $payableHours,
            $status,
        ];
    }

    protected function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }
    protected function calculatePayableHours($totalDuration, $date)
    {
        $date = Carbon::parse($date);
        // Convert totalDuration from seconds to hours
        $totalHours = $totalDuration / 3600;
        // Check if the date is a weekend
        if ($date->isWeekend()) {
            return '08:00';
        }
        if ($totalHours >= 8) {
            return '08:00';
        } elseif ($totalHours >= 4) {
            return '04:00';
        } else {
            return '00:00';
        }
    }
    public function statusFormation($date, $status, $holidayReason)
    {
        $date = Carbon::parse($date);

        // Check if the date is a weekend
        if ($date->isWeekend()) {
            return 'Weekend';
        }

        // Check if the date is a holiday
        if ($holidayReason) {
            return $holidayReason;
        }
        if ($status == 'pp')
            return 'Present';
        elseif ($status == 'pa')
            return 'Present-Absent';
        elseif ($status == 'ap')
            return 'Absent-Present';
        elseif ($status == 'aa')
            return 'Absent';
        else
            return ' ';
    }
}
