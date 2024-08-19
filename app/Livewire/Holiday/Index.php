<?php

namespace App\Livewire\Holiday;

use App\Models\Holiday;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'date'; // Default sorting column
    public $sortDirection = 'asc'; // Default sorting direction
    public $isRestricted = ''; // New property for filtering

    protected $queryString = ['search', 'perPage', 'sortBy', 'sortDirection', 'isRestricted'];

    public function render()
    {
        $query = Holiday::query()
            ->where(function($q) {
                $q->where('reason', 'like', '%' . $this->search . '%')
                  ->orWhere('date', 'like', '%' . $this->search . '%')
                  ->orWhere('is_restricted', 'like', '%' . $this->search . '%');
            });

        if ($this->isRestricted !== '') {
            $query->where('is_restricted', $this->isRestricted);
        }

        $holidays = $query->orderBy($this->sortBy, $this->sortDirection)
                           ->paginate($this->perPage);

        return view('livewire.holiday.index', [
            'holidays' => $holidays
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'search' || $propertyName === 'perPage' || $propertyName === 'isRestricted') {
            $this->resetPage();
        }
    }
   
}
