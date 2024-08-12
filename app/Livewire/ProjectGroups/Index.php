<?php

namespace App\Livewire\ProjectGroups;

use App\Models\ProjectGroup;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
    }
    public function delete($id)
{
    $group = ProjectGroup::findOrFail($id);
    $group->delete();

    Session::flash('message', 'Project group deleted successfully.');
}

    public function render()
    {
        $projectGroups = ProjectGroup::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.project-groups.index', [
            'projectGroups' => $projectGroups,
        ]);
    }
    
}
