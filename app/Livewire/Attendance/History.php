<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\AttendanceEntry;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;
use Livewire\Component;

class History extends Component
{
    #[Url()]
    public $date;

    
    public $attendancePairs = [];

    public function mount()
    {
        $this->date = Carbon::today()->toDateString();
        $this->loadEntries();
    }

    public function updatedDate()
    {
        $this->loadEntries();
    }

    public function loadEntries()
    {
        $this->attendancePairs = [];
    
        // Get the authenticated employee's ID
        $employeeId = auth()->guard('employee')->user()->id;
    
        // Fetch attendance for the authenticated employee on the selected date
        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $this->date)
            ->first();
    
        if ($attendance) {
            // Get the attendance entries for the employee, filtering by entry_type (0 for check-out, 1 for check-in)
            $attendanceEntries = $attendance->attendanceEntries()
                ->whereIn('entry_type', [0, 1]) // Only check-in and check-out entries
                ->orderBy('created_at')
                ->with('attendance.employee', 'modifiedBy') // Eager load employee and modified by employee
                ->get();
    
            $checkInTimes = [];
            $checkOutTimes = [];
    
            // Organize the entries into check-ins and check-outs
            foreach ($attendanceEntries as $entry) {
                $entry->attendance->date = \Carbon\Carbon::parse($entry->attendance->date); // Convert date to Carbon instance
    
                if ($entry->entry_type == 1) {
                    $checkInTimes[] = $entry;
                } elseif ($entry->entry_type == 0) {
                    $checkOutTimes[] = $entry;
                }
            }
    
            // Create pairs of check-in and check-out entries
            $pairsCount = min(count($checkInTimes), count($checkOutTimes));
    
            for ($i = 0; $i < $pairsCount; $i++) {
                $isModified = $checkInTimes[$i]->updated_at != $checkInTimes[$i]->created_at || $checkOutTimes[$i]->updated_at != $checkOutTimes[$i]->created_at;
                
                // Store each pair in the attendancePairs array, including modification information
                $this->attendancePairs[] = [
                    'checkIn' => $checkInTimes[$i],
                    'checkOut' => $checkOutTimes[$i],
                    'isModified' => $isModified,
                    'modifiedBy' => $isModified ? $checkInTimes[$i]->modifiedBy : null,
                ];
            }
        }
    }
    
    

    /**
     * Checks if an attendance entry has been modified.
     *
     * @param  mixed  $checkIn
     * @param  mixed  $checkOut
     * @return bool
     */
    protected function isModified($checkIn, $checkOut)
    {
        return $checkIn->wasChanged() || $checkOut->wasChanged();
    }
    

    public function render()
    {
        return view('livewire.attendance.history');
    }
}

