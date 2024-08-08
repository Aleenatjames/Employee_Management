<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $showSecondarySidebar = false;

    public function toggleSecondarySidebar()
    {
        $this->showSecondarySidebar = !$this->showSecondarySidebar;
    }

    public function hideSecondarySidebar()
    {
        $this->showSecondarySidebar = false;
    }

    protected $listeners = ['hideSecondarySidebar'];

    public function render()
    {
        return view('livewire.sidebar');
    }
}
