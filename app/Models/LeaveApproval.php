<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    use HasFactory;
    protected $fillable = [
        'application_id',
        'date',
        'approver_id',
        'status',
        'comment',
        'level'
    ];

    public function leaveApplication()
    {
        return $this->belongsTo(LeaveApplication::class, 'application_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approver_id');
    }
}
