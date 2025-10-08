<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Portal extends Component
{
    public $isRegister = false;

    // Login
    public $email;

    public $password;

    public $remember = false;

    // Register
    public $name;

    public $emailReg;

    public $passwordReg;

    public $password_confirmation;

    public function toggleForm()
    {
        $this->isRegister = ! $this->isRegister;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect('/dashboard');
        }

        $this->addError('email', 'Invalid credentials.');
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|min:3',
            'emailReg' => 'required|email|unique:users,email',
            'passwordReg' => 'required|min:6|same:password_confirmation',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->emailReg,
            'password' => Hash::make($this->passwordReg),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.portal');
    }
}
