<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $table = 'attendance';
    protected $fillable = [
        'employee_id',
        'date',
        'status'
    ];
    public function attendanceEntries()
    {
        return $this->hasMany(AttendanceEntry::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
}
