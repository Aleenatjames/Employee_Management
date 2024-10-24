<?php

namespace App\Livewire\LeaveTracker;

use App\Models\DivisionChild;
use App\Models\DivisionLeaveType;
use App\Models\DivisionParent;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateLeave extends Component
{
    public $code;
    public $name;
    public $is_payable;
    public $is_carry_over;
    public $incremental_type;
    public $application_timing='any';
    public $leave_type_id;
    public $leaveTypes;
    public $parent_id;
    public $child_id;

    public $existingParents = []; 
    public Collection $childDivisions;
    public $incremental_count;
    public $rules = [
        'leave_type_id' => 'required|exists:leave_types,id',
        'child_id' => 'required|exists:division_children,id',
        'incremental_count' => 'required|numeric|min:1', // Assuming the count should be at least 1
    ];
    public function mount()
    {
        // Load the parent and child divisions initially
        $this->existingParents = DivisionParent::all(); // Assuming parent divisions have parent_id = null
        $this->leaveTypes = LeaveType::all();
        $this->childDivisions = new Collection(); 
       
    }

    public function updatedLeaveTypeId($value)
    {
        $leaveType = LeaveType::with('parent')->find($value);
    
        // Log the selected leave type
        Log::info('Selected Leave Type:', ['leaveTypeId' => $value, 'leaveType' => $leaveType]);
    
        if ($leaveType && $leaveType->applicable_division) {
            // Fetch child divisions based on applicable_division
            $this->childDivisions = DivisionChild::where('parent_id', $leaveType->applicable_division)->get();
    
            // Log the fetched child divisions
            Log::info('Child divisions fetched: ', $this->childDivisions->toArray());
        } else {
            $this->childDivisions = new Collection(); // Reset as an empty Eloquent Collection
        }
    }
    

    

    public function store(){
      
        $this->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:100',
            'is_payable' => 'required|boolean',
            'is_carry_over' => 'required|boolean',
            'incremental_type' => 'required|in:mon,qua,h,y',
            'application_timing' => 'required|in:any,before,after',
            'parent_id' => 'nullable|exists:division_parents,id',
      

        ]);

        LeaveType::create([
            'code' => $this->code,
            'name' => $this->name,
            'is_payable' => $this->is_payable,
            'is_carry_over' => $this->is_carry_over,
            'incremental_type' => $this->incremental_type,
            'application_timing' => $this->application_timing,
            'applicable_division' => $this->parent_id,

        ]);
        $this->reset(['code', 'name', 'is_payable', 'is_carry_over', 'incremental_type', 'application_timing', 'parent_id']);

        // Show success message
        session()->flash('message', 'Leave type created successfully!');
        
    } 
    public function storeDivisionLeaveType()
    {

        DivisionLeaveType::create([
            'leave_type' => $this->leave_type_id,
            'child_id' => $this->child_id,
            'incremental_count' => $this->incremental_count,
        ]);

        session()->flash('message', 'Division Leave Type created successfully.');

        // Optionally, reset form fields
        $this->reset(['leave_type_id', 'child_id', 'incremental_count']);
    }
    public function render()
    {
        return view('livewire.leave-tracker.create-leave');
    }
}
