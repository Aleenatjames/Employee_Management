<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'email',
        'password',
        'reporting_manager',

    ];
    public function employeeDetails()
{
    return $this->hasOne(EmployeeDetails::class, 'employee_id');
}
}
