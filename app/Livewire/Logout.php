<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class Logout extends Component
{ 
    public function logout()
    {
        Auth::guard('employee')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('employee.login');
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
