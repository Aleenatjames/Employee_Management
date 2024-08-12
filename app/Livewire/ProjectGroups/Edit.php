<?php

namespace App\Livewire\ProjectGroups;

use App\Models\ProjectGroup;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Edit extends Component
{
    public $groupId;
    public $code;
    public $name;
    public $isProject;

    protected $rules=[
        'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'isProject' => 'boolean',
    ];

    public function mount($groupId){
        $group=ProjectGroup::FindOrFail($groupId);
        $this->groupId=$group->id;
        $this->code=$group->code;
        $this->name=$group->name;
        $this->isProject=$group->isProject;
    }

    public function update(){
        $this->validate();

        $group=ProjectGroup::FindOrFail($this->groupId);

        $group->update([
            'code'=>$this->code,
            'name'=>$this->name,
            'isProject'=>$this->isProject
        ]);
        Session::flash('message', 'Project group updated successfully.');
        return redirect()->route('employee.project-groups.index');
    }
    public function render()
    {
        return view('livewire.project-groups.edit');
    }
}
