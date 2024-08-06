<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'head'
    ];
    public function departmentHead()
    {
        return $this->belongsTo(Employee::class, 'head'); // 'head' is the foreign key column in the departments table
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_department');
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }
    public function parentDepartment()
{
    return $this->belongsTo(Department::class, 'parent_id');
}

}
