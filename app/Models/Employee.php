<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles;
    
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'email',
        'password',
        
        'status',
        'designation'

    ];
    protected $guard_name = 'web';
    public function employeeDetails()
{
    return $this->hasOne(EmployeeDetails::class, 'employee_id');
}
public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'employee_department');
    }

    
}
