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
    


    public function update(Request $request, $attendanceId)
    {
        // Validate the incoming request
        $request->validate([
            'check_in_times' => 'required|array',
            'check_in_times.*' => 'required|date_format:H:i:s',
            'check_out_times' => 'required|array',
            'check_out_times.*' => 'required|date_format:H:i:s',
        ]);
    
        Log::info('Update method called', ['attendanceId' => $attendanceId]);
    
        // Find the attendance record
        $attendance = Attendance::findOrFail($attendanceId);
        Log::info('Attendance record found', ['attendance' => $attendance]);
    
        // Fetch the attendance entries related to this attendanceId
        $attendanceEntries = AttendanceEntry::where('attendance_id', $attendanceId)->get();
        Log::info('Fetched attendance entries', ['entries' => $attendanceEntries]);
    
        // Create an array for check-in and check-out times from the request
        $checkInTimes = $request->input('check_in_times');
        $checkOutTimes = $request->input('check_out_times');
    
        Log::info('Incoming check-in times', ['checkInTimes' => $checkInTimes]);
        Log::info('Incoming check-out times', ['checkOutTimes' => $checkOutTimes]);
    
        // Track changes to verify correct update
        $checkInIndex = 0;
        $checkOutIndex = 0;
    
        $lastCheckInTime = null;
        $lastCheckOutTime = null;
    
        foreach ($attendanceEntries as $entry) {
            if ($entry->entry_type == 1) { // Check-In
                if (isset($checkInTimes[$checkInIndex])) {
                    $newCheckInTime = $checkInTimes[$checkInIndex];
        
                    // Only update if the new time is different from the existing time
                    if ($entry->entry_time != $newCheckInTime) {
                        Log::info('Updating check-in time', [
                            'entry_id' => $entry->id,
                            'old_time' => $entry->entry_time,
                            'new_time' => $newCheckInTime,
                        ]);
        
                        $entry->entry_time = $newCheckInTime;
                        $entry->modified_by = auth()->guard('employee')->id(); // Set the user who modified    
                        $entry->save();
        
                        // Check for the next entry (check-out)
                        if (isset($attendanceEntries[$checkInIndex + 1]) && $attendanceEntries[$checkInIndex + 1]->entry_type == 0) {
                            $checkOutEntry = $attendanceEntries[$checkInIndex + 1];
                            $checkOutTime = Carbon::parse($checkOutEntry->entry_time);
                            $newCheckInTimeParsed = Carbon::parse($newCheckInTime);
                            $duration = abs($checkOutTime->diffInSeconds($newCheckInTimeParsed));
        
                            Log::info('Updating duration for check-in adjustment', [
                                'check_out_entry_id' => $checkOutEntry->id,
                                'duration' => $duration,
                            ]);
        
                            // Update the duration for the corresponding check-out entry
                            $checkOutEntry->duration = $duration;
                            $checkOutEntry->save();
                        }
        
                        $lastCheckInTime = Carbon::parse($newCheckInTime);
                    }
                    $checkInIndex++;
                }
            }
        
            if ($entry->entry_type == 0) { // Check-Out
                if (isset($checkOutTimes[$checkOutIndex])) {
                    $newCheckOutTime = $checkOutTimes[$checkOutIndex];
        
                    // Only update if the new time is different from the existing time
                    if ($entry->entry_time != $newCheckOutTime) {
                        Log::info('Updating check-out time', [
                            'entry_id' => $entry->id,
                            'old_time' => $entry->entry_time,
                            'new_time' => $newCheckOutTime,
                        ]);
        
                        $entry->entry_time = $newCheckOutTime;
                        $entry->modified_by = auth()->guard('employee')->id(); // Set the user who modified
                        $entry->save();
        
                        // Calculate and save the duration
                        if ($lastCheckInTime) {
                            $checkOutTime = Carbon::parse($newCheckOutTime);
                            $duration = abs($checkOutTime->diffInSeconds($lastCheckInTime));
        
                            Log::info('Updating duration for check-out', [
                                'entry_id' => $entry->id,
                                'duration' => $duration,
                            ]);
        
                            $entry->duration = $duration;
                            $entry->save();
                        }
        
                        $lastCheckInTime = Carbon::parse($newCheckOutTime);
                    }
                    $checkOutIndex++;
                }
            }
        }
        
    
        // Redirect back with a success message
        return redirect()->route('employee.attendance')->with('success', 'Attendance times updated successfully.');
    }
    
    public function history(){
        return view('employee.attendance.history');
    }
    public function calendar(){
        return view('employee.attendance.calendar');
    }
    
}
