<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends Model
{
    use HasFactory;
    protected $table='project_groups';
    protected $fillable=[
        'code',
        'name',
        'isProject'
    ];
}
