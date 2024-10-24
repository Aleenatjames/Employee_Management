<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    protected $table = 'leave_application';


    protected $fillable = ['employee_id', 'leave_type', 'from_date', 'to_date', 'lastupdated_by', 'reason', 'no_of_days', 'status'];
    use HasFactory;

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); // Ensure 'employee_id' is the foreign key
    }
    // LeaveApplication model
    public function leaveApplicationDates()
    {
        return $this->hasMany(LeaveApplicationDate::class, 'application_id');
    }
    public function lastupdated()
    {
        return $this->belongsTo(Employee::class, 'lastupdated_by');
    }
}
