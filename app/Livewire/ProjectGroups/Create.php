<?php

namespace App\Livewire\ProjectGroups;

use App\Models\ProjectGroup;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Create extends Component
{
    public $code;
    public $name;
    public $isProject = false;

    protected $rules = [
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'isProject' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        ProjectGroup::create([
            'code' => $this->code,
            'name' => $this->name,
            'isProject' => $this->isProject,
        ]);

        Session::flash('message', 'Project group created successfully.');
        return redirect()->route('employee.project-groups.index');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.project-groups.create');
       
    }
}
