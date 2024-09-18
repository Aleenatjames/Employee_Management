<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Calendar extends Component
{
    public $currentDate;
    public $monthYear;
    public $days;
    public $attendances;
    public $holidays;

    public function mount()
    {
        // Set current date to today's date initially
        $this->currentDate = Carbon::now();
        $this->generateCalendarData();
    }

    // Function to move to the previous month
    public function previousMonth()
    {
        $this->currentDate = $this->currentDate->subMonth();
        $this->generateCalendarData();
    }

    // Function to move to the next month
    public function nextMonth()
    {
        $this->currentDate = $this->currentDate->addMonth();
        $this->generateCalendarData();
    }

    // Generate the days for the current month
    private function generateCalendarData()
    {
        $this->monthYear = $this->currentDate->format('F Y');
        
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        
        $startOfWeek = $startOfMonth->copy()->startOfWeek();
        $endOfWeek = $endOfMonth->copy()->endOfWeek();

        $days = [];
        $currentWeek = [];

        while ($startOfWeek <= $endOfWeek) {
            for ($i = 0; $i < 7; $i++) {
                $dayData = [
                    'day' => $startOfWeek->day,
                    'currentMonth' => $startOfWeek->month == $this->currentDate->month,
                ];
                $currentWeek[] = $dayData;
                $startOfWeek->addDay();
            }
            $days[] = $currentWeek;
            $currentWeek = [];
        }

        $this->days = $days;
        $this->attendances = $this->getAttendancesForCurrentMonth();
        $this->holidays = $this->getHolidaysForCurrentMonth();
    }

    // Fetch attendance data for the current month from the database
    private function getAttendancesForCurrentMonth()
    {

        $employee = Auth::guard('employee')->user(); // Get the logged-in employee

        // Get the current month and year
        $currentMonth = $this->currentDate->month;
        $currentYear = $this->currentDate->year;

        // Fetch attendance records for the current month and the logged-in employee
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        // Map the fetched data into a collection
        return $attendances->mapWithKeys(function ($attendance) {
            return [
                $attendance->date => (object)[
                    'status' => $attendance->status
                ]
            ];
        });
    }
    private function getHolidaysForCurrentMonth()
    {
        // Get the current month and year
        $currentMonth = $this->currentDate->month;
        $currentYear = $this->currentDate->year;
    
        // Fetch holiday records from the `holidays` table for the current month and year
        $holidays =Holiday::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();
    
        // Map the fetched data into a collection
        return $holidays->mapWithKeys(function ($holiday) {
            return [
                $holiday->date => (object)[
                    'name' => $holiday->reason,
                    'is_restricted' => $holiday->is_restricted
                ]
            ];
        });
    }
    

    public function render()
    {
        return view('livewire.attendance.calendar');
    }
}

