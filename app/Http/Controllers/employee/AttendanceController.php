<?php

namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = auth()->guard('employee')->user()->id;
       
        return view('employee.attendance.list', [
           
            'employeeId' => $employeeId,
           
        ]);
    }

    public function toggleCheckInStatus(Request $request)
    {
        $user = Auth::guard('employee')->user();
        $isCheckedIn = $request->input('isCheckedIn');
        Log::info('Request check-in ' . $isCheckedIn . ' by employee ' . $user->id);
        
        // Get or create today's attendance record
        $todayAttendance = Attendance::firstOrCreate(
            ['employee_id' => $user->id, 'date' => Carbon::today()],
            ['date' => Carbon::today(), 'employee_id' => $user->id]
        );
    
        // Create a new attendance entry
        $entry = new AttendanceEntry();
        $entry->attendance_id = $todayAttendance->id;
        $entry->entry_type = $isCheckedIn;
        $entry->entry_time = now();
    
        // If this is a check-out, calculate the duration since the last check-in
        if (!$isCheckedIn) { // Check-out
            $lastCheckin = AttendanceEntry::where('attendance_id', $todayAttendance->id)
                ->where('entry_type', true) // Check-in
                ->latest('entry_time')
                ->first();
    
            if ($lastCheckin) {
                $entryTime = Carbon::now(); // Current time for the check-out
                $lastCheckinTime = Carbon::parse($lastCheckin->entry_time);
    
                // Ensure entryTime is after lastCheckinTime
                if ($entryTime->greaterThan($lastCheckinTime)) {
                    $duration = $entryTime->diffInSeconds($lastCheckinTime);
                    $entry->duration = abs($duration); // Ensure duration is positive
                } else {
                    Log::error("Check-out time is before the last check-in time for employee " . $user->id);
                    return response()->json(['success' => false, 'message' => 'Invalid check-out time'], 400);
                }
            } else {
                Log::error("Check-out exception: No corresponding check-in found for employee " . $user->id);
                return response()->json(['success' => false, 'message' => 'No corresponding check-in found'], 500);
            }
        }
    
        // Save the attendance entry
        $entry->save();
    
        return response()->json(['success' => true]);
    }

    public function line(){
        return view('employee.attendance.line');
    }

}
