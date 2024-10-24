<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAllocation extends Model
{
    protected $table='leave_allocations';

  
    protected $fillable = ['employee_id', 'leave_type', 'allocated_days', 'used'];
    use HasFactory;
}
