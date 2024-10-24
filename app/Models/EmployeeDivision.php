<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDivision extends Model
{
    use HasFactory;
  
    protected $table='employee_divisions';
    protected $fillable=[
        'employee_id',
        'parent_id',
        'child_id'
    ];
    public function divisionParent()
    {
        return $this->belongsTo(DivisionParent::class, 'parent_id');
    }

    public function divisionChild()
    {
        return $this->belongsTo(DivisionChild::class, 'child_id');
    }
}
