<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEntry extends Model
{
    use HasFactory;
    protected $table = 'attendance_entries';
    protected $fillable = [
        'attendance_id',
        'entry_type',
        'entry_time',
        'duration',
        'modified_by'

    ];
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
