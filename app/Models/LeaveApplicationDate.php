<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplicationDate extends Model
{
    protected $table='leave_application_date';

    protected $fillable=['application_id','date','duration'];
    use HasFactory;

    public function leaveApplication()
{
    return $this->belongsTo(LeaveApplication::class, 'application_id');
}
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}
}
