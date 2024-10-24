<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionLeaveType extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table='division_leave_types';
    protected $fillable=[
        'leave_type',
        'child_id',
        'incremental_count'
    ];
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type'); // Adjust 'leave_type_id' as necessary
    }

    // If there's a relationship to Division, you can also define it here
    public function division()
    {
        return $this->belongsTo(DivisionChild::class, 'child_id'); // Adjust 'child_id' as necessary
    }

public function divisionChild()
{
    return $this->belongsTo(DivisionChild::class, 'Division_child');
}
}
