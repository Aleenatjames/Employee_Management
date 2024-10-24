<?php

namespace App\Livewire\LeaveTracker;

use App\Models\DivisionChild;
use App\Models\DivisionParent;
use App\Models\Employee; 
use App\Models\EmployeeDivision;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class DivisionForm extends Component
{
    public $create_new_parent = true;  
    public $parent_name;
    public $parent_id = null; 
    public $child_name;

    public $existingParents = [];  // For selecting existing parents

    public function mount()
    {
        // Load all existing parent divisions initially
        $this->existingParents = DivisionParent::all();
    }

    public function updatedCreateNewParent($value)
    {
        // If "Select Existing Parent" is chosen, fetch all existing parents immediately
        if (!$value) {
            $this->existingParents = DivisionParent::all();
        }
    }

    public function updatedParentId()
    {
        // If an existing parent is selected, we could implement logic here if needed
        // For now, it's not required since we are only creating a new child division
    }

    public function store()
    {
        // Validation for parent and child creation/selection
        $this->validate([
            'create_new_parent' => 'required|boolean',
            'parent_name' => 'required_if:create_new_parent,true|nullable|string|unique:division_parents,name',
            'parent_id' => 'required_if:create_new_parent,false|exists:division_parents,id|nullable',
            'child_name' => 'required|string|unique:division_children,name',
        ]);

        // Step 1: Handle parent division creation or selection
        if ($this->create_new_parent) {
            // Create a new parent division
            $parent = DivisionParent::create([
                'name' => $this->parent_name,
            ]);
        } else {
            // Use selected existing parent division
            $parent = DivisionParent::find($this->parent_id);
        }

        // Step 2: Always create a new child division
        DivisionChild::create([
            'name' => $this->child_name,
            'parent_id' => $parent->id,  // Associate the new child with the selected parent
        ]);

        // Reset form fields after submission
        $this->reset([
            'parent_name', 'parent_id', 'child_name', 'create_new_parent'
        ]);

        // Flash a success message to the session
        session()->flash('message', 'Division created successfully!');
    }

    public function render()
    {
        return view('livewire.leave-tracker.division-form', [
            'existingParents' => $this->existingParents,
        ]);
    }
}


