<?php

namespace App\Livewire\LeaveTracker;

use App\Models\DivisionChild;
use App\Models\DivisionLeaveType;
use App\Models\DivisionParent;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Edit extends Component
{
    public $leaveId;
    public $code;
    public $name;
    public $is_payable;
    public $is_carry_over;
    public $incremental_type;
    public $child_id;
    public $incremental_count;
    public $application_timing;
    PUBLIC $applicable_division;
    public $divisionLeaveTypes = [];
    public $newDivisionId = null;
    public $newIncrementalCount = null;
    public $availableDivisions = [];



    public function mount($leaveId)
    {
        if ($leaveId) {
            // Fetch existing data
            $leaveType = LeaveType::findOrFail($leaveId);
            $this->leaveId = $leaveType->id;
            $this->code = $leaveType->code;
            $this->name = $leaveType->name;
            $this->is_payable = $leaveType->is_payable;
            $this->is_carry_over = $leaveType->is_carry_over;
            $this->incremental_type = $leaveType->incremental_type;
            $this->application_timing = $leaveType->application_timing;
            $this->applicable_division=$leaveType->applivable_division;
    
            // Fetch all division leave types associated with this leave type
            $this->divisionLeaveTypes = DivisionLeaveType::where('leave_type', $leaveId)->get();

            $this->applicable_division=DivisionParent::all();
        }
        $this->divisionLeaveTypes = DivisionLeaveType::where('leave_type', $leaveId)
        ->get()
        ->map(function ($divisionLeaveType) {
            return [
                'id' => $divisionLeaveType->id,
                'division_name' => $divisionLeaveType->division->name ?? 'No Division',
                'incremental_count' => $divisionLeaveType->incremental_count,
            ];
        })->toArray();

        if ($leaveType && $leaveType->applicable_division) {
            // Fetch child divisions based on the applicable_division
            $this->availableDivisions = DivisionChild::where('parent_id', $leaveType->applicable_division)->get();
    
            // Log the fetched child divisions for debugging purposes
           
        } else {
            // If no applicable division is found, clear the child divisions
            $this->availableDivisions = [];
            Log::info('No applicable division found or leave type is null.');
        }
    }
    

    public function store()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'is_payable' => 'required',
            'is_carry_over' => 'required',
            'incremental_type' => 'required',
            'application_timing' => 'required',
            'applicable_division'=>'required|nullable',
            'divisionLeaveTypes.*.incremental_count' => 'required|numeric|nullable',
        ]);
    
        Log::info('Storing LeaveType', [
            'code' => $this->code,
            'name' => $this->name,
            'is_payable' => $this->is_payable,
            'is_carry_over' => $this->is_carry_over,
            'incremental_type' => $this->incremental_type,
            'application_timing' => $this->application_timing,
            'divisionLeaveTypes' => $this->divisionLeaveTypes,

        ]);
    
        if ($this->leaveId) {
            // Update LeaveType
            $leaveType = LeaveType::findOrFail($this->leaveId);
            $leaveType->update([
                'code' => $this->code,
                'name' => $this->name,
                'is_payable' => $this->is_payable,
                'is_carry_over' => $this->is_carry_over,
                'incremental_type' => $this->incremental_type,
                'application_timing' => $this->application_timing,
                
            ]);
    
      // Log the divisionLeaveType IDs for debugging
      foreach ($this->divisionLeaveTypes as $divisionLeaveTypeData) {
        Log::info('Processing DivisionLeaveType', [
            'divisionLeaveTypeId' => $divisionLeaveTypeData['id'],
            'incremental_count' => $divisionLeaveTypeData['incremental_count'],
        ]);

        $divisionLeaveType = DivisionLeaveType::find($divisionLeaveTypeData['id']); // Find by ID
        if ($divisionLeaveType) {
            // Log before updating
            Log::info('Updating DivisionLeaveType', [
                'id' => $divisionLeaveType->id,
                'old_incremental_count' => $divisionLeaveType->incremental_count,
                'new_incremental_count' => $divisionLeaveTypeData['incremental_count'],
            ]);

            // Update the incremental_count
            $divisionLeaveType->update([
                'incremental_count' => $divisionLeaveTypeData['incremental_count'],
            ]);
        } else {
            Log::error('DivisionLeaveType not found', ['id' => $divisionLeaveTypeData['id']]);
        }
    }
        }

        // Redirect after successful update
        session()->flash('message', 'Leave Type and Division Leave Types updated successfully.');
        return redirect()->route('employee.leave.index');
    }
    
    
    public function render()
    {
        return view('livewire.leave-tracker.edit');
    }
}
