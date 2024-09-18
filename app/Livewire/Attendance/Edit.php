<?php

namespace App\Livewire\Attendance;

use Livewire\Component;

class Edit extends Component
{
  
    
    public function mount()
    {
        // Initialize the attendanceId property
   
        // You can fetch the attendance record here if needed
        // $this->attendance = Attendance::find($attendanceId);
    }
    public function render()
    {
        return view('livewire.attendance.edit');
    }
}
