<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'pm',
        'start_date',
        'end_date',
        'description',
        'status',
        'group_id'


    ];
    public function projectManager()
{
    return $this->belongsTo(Employee::class, 'pm');
}
public function allocations()
    {
        return $this->hasMany(ProjectAllocation::class);
    }
    public function projectGroup()
    {
        return $this->belongsTo(ProjectGroup::class, 'id');
    }
    public function group()
    {
        return $this->belongsTo(ProjectGroup::class, 'group_id');
    }
}
