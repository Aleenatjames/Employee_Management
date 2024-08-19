<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimesheet extends Model
{
    use HasFactory;
    protected $table='project_timesheet';

    protected $fillable=[
        'employee_id',
        'project_id',
        'date',
        'is_taskid',
        'taskid',
        'time',
        'comment'
    ];
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
