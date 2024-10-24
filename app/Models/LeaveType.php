<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    use HasFactory;
  
    protected $fillable=[
        'code',
        'name',
        'is_payable',
        'is_carry_over',
        'incremental_type',
        'application_timing',
        'applicable_division'
    ];
    public function parent()
    {
        // Adjust 'applicable_division' if this is the foreign key in the leave_types table
        return $this->belongsTo(DivisionParent::class, 'applicable_division');
    }

    // Define the relationship to the child divisions
    public function childDivisions()
    {
        // Adjust based on your DB structure
        return $this->hasMany(DivisionChild::class, 'parent_id', 'applicable_division');
    }
    public function division()
{
return $this->belongsTo(DivisionParent::class, 'applicable_division');
}
// Relationship to get the child division (assuming you store the child ID in the leave_type)
public function childDivision()
{
return $this->belongsTo(DivisionChild::class, 'child_division_id'); // Adjust 'child_division_id' to your actual column name
}
public function divisionLeaveTypes()
{
return $this->hasMany(DivisionLeaveType::class, 'leave_type'); // Adjust 'leave_type_id' as necessary
}

}
