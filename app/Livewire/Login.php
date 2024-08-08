<?php

namespace App\Livewire;

use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ];

    public function render()
    {
        return view('livewire.login');
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::guard('employee')->attempt($credentials, $this->remember)) {
            return redirect()->route('employee.dashboard'); // Redirect to the dashboard after login
        } else {
           Session::flash('error', 'Invalid email or password.');
        }
    }
}
