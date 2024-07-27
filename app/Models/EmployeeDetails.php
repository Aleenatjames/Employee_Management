<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetails extends Model
{
    use HasFactory;
    protected $table = 'employee_details';
    protected $fillable = [
        'name',
        'profile_picture',
        'dob',
        'blood_group',
        'address',
        'mobile_no',
        'employee_id'

    ];
    public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}
}
