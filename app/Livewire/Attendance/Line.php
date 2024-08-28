<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use Carbon\Carbon;
use Livewire\Component;

class Line extends Component
{
    public $attendanceData = [];
    protected $casts = [
        'date' => 'date:Y-m-d', // Cast 'date' as a Carbon instance
    ];

    public function mount()
    {
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $employeeId = auth()->guard('employee')->user()->id;
    
        // Fetch attendance data for the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
    
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->with('attendanceEntries')
            ->get();
    
        $this->attendanceData = $attendances->map(function ($attendance) {
            $entries = $attendance->attendanceEntries->sortBy('entry_time');
    
            $checkInTimes = $entries->where('entry_type', 1)->pluck('entry_time')->all();
            $checkOutTimes = $entries->where('entry_type', 0)->pluck('entry_time')->all();
    
            return [
                'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                'checkIns' => $checkInTimes,
                'checkOuts' => $checkOutTimes,
            ];
        })->toArray();
    }
    
    public function render()
    {
        return view('livewire.attendance.line');
    }
}
