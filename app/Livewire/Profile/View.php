<?php

namespace App\Livewire\Profile;

use App\Models\Employee;
use Livewire\Component;


class View extends Component
{
    public $employee;

    public function mount(){
        $this->employee=auth()->guard('employee')->user();
    }
    public function render()
    {
        return view('livewire.profile.view',[
            'employee'=>$this->employee,
        ]);
    }
}
