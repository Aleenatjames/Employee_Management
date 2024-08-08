<?php

namespace App\Livewire\ProjectAllocation;

use App\Models\ProjectAllocation;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'start_date';
    public $sortDirection = 'asc';

    public function render()
    {
        $allocations = ProjectAllocation::with(['project', 'employee', 'role', 'allocatedBy'])
            ->where(function($query) {
                $query->whereHas('project', function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('employee', function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('role', function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.project-allocation.index', [
            'allocations' => $allocations,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete($id)
    {
        ProjectAllocation::find($id)->delete();
        Session::flash('message', 'Allocation deleted successfully.');
    }
}
