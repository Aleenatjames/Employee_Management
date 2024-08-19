<?php

namespace App\Livewire\Holiday;

use App\Models\Holiday;
use Livewire\Component;

class Edit extends Component
{
    public $holidayId;
    public $date;
    public $reason;
    public $type;
    public $is_restricted;

    // Rename the mount method to expect a property instead of parameter
    public function mount($holidayId)
    {
        $holiday = Holiday::findOrFail($holidayId);
        $this->holidayId = $holiday->id;
        $this->date = $holiday->date;
        $this->reason = $holiday->reason;
        $this->type = $holiday->type;
        $this->is_restricted = $holiday->is_restricted;
    }

    protected $rules = [
        'date' => 'required|date',
        'reason' => 'required|string|max:255',
        'type' => 'required|in:public,company',
        'is_restricted' => 'required|in:yes,no',
    ];

    public function updateHoliday()
    {
        $this->validate();

        $holiday = Holiday::findOrFail($this->holidayId);
        $holiday->update([
            'date' => $this->date,
            'reason' => $this->reason,
            'type' => $this->type,
            'is_restricted' => $this->is_restricted,
        ]);

        session()->flash('message', 'Holiday updated successfully.');
        return redirect()->route('holiday.index');
    }

    public function render()
    {
        return view('livewire.holiday.edit');
    }
}
