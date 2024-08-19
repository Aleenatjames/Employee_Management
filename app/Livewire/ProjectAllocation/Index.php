<?php

namespace App\Livewire\ProjectAllocation;

use App\Models\Project;
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
    public $startDate;
    public $endDate;
    public $selectedProject;

    public function render()
    {
        // Start building the query
        $query = ProjectAllocation::with(['project', 'employee', 'role', 'allocatedBy'])
            ->where(function ($query) {
                // Apply search filters to related models
                $query->whereHas('project', function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('employee', function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('role', function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%');
                });
            });

        // Apply date filters if provided
        if ($this->startDate) {
            $query->whereDate('start_date', '<=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('end_date', '>=', $this->endDate);
        }

        // Apply project filter if provided
        if ($this->selectedProject) {
            $query->where('project_id', $this->selectedProject);
        }

        // Apply sorting and paginate
        $allocations = $query->orderBy($this->sortField, $this->sortDirection)
                             ->paginate($this->perPage);

        // Fetch all projects for the project dropdown
        $projects = Project::all();

        // Return the view with allocations and projects
        return view('livewire.project-allocation.index', [
            'allocations' => $allocations,
            'projects' => $projects,
        ]);
    }

    // Method triggered on Search button click
    public function searchWithFilters()
    {
        $this->resetPage(); // Reset pagination when a filter is applied
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
