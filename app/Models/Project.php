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


    ];
    public function projectManager()
{
    return $this->belongsTo(Employee::class, 'pm');
}
}
