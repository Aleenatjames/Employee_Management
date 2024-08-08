<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAllocation extends Model
{
    use HasFactory;
    protected $table='project_allocations';
    protected $fillable=[
        'project_id',
        'employee_id',
        'role_id',
        'allocated_by',
        'start_date',
        'end_date'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(ProjectRole::class, 'role_id');
    }
    public function allocatedBy()
    {
        return $this->belongsTo(Employee::class, 'allocated_by');
    }

}
