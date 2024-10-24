<?php

namespace App\Livewire\LeaveTracker;

use App\Models\DivisionLeaveType;
use App\Models\LeaveType;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name'; // Default sorting column
    public $sortDirection = 'asc';

    public function render()
    {
        $query = LeaveType::query()
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('is_payable', 'like', '%' . $this->search . '%');
            });
            $divisionLeaveType = DivisionLeaveType::with('leaveType') // Eager load leaveType relationship
            ->whereHas('leaveType', function($q) use ($query) {
                $q->whereIn('id', $query->pluck('id')); // Only include leaveTypes in the query
            })
            ->get();
            $leaveTypes = $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.leave-tracker.index',[
            'leaveTypes' =>$leaveTypes,
            'divisionLeaveType' =>$divisionLeaveType
        ]);
    }
}
