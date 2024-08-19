<?php

namespace App\Livewire\Holiday;

use App\Models\Holiday;
use Livewire\Component;

class Create extends Component
{
    public $date;
    public $reason;
    public $type;
    public $is_restricted;

    protected $rules = [
        'date' => 'required|date',
        'reason' => 'required|string|max:255',
        'type' => 'required',
        'is_restricted' => 'required|in:yes,no',
    ];

    public function createHoliday()
    {
        $this->validate();
       

        Holiday::create([
            'date' => $this->date,
            'reason' => $this->reason,
            'type' => $this->type,
            'is_restricted' => $this->is_restricted,
        ]);

        session()->flash('message', 'Holiday created successfully.');
        return redirect()->route('holiday.index');

        // Clear the form inputs after submission
        $this->reset();
    }

    public function render()
    {
        return view('livewire.holiday.create');
    }
}
