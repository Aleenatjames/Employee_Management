<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRole extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'project_roles';
    protected $fillable = [
        'name',
        'code'


    ];
}
