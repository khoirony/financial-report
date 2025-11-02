<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class Login extends Component
{
    public $email = '';

    public $password = '';

    public $remember = false;

    // Aturan validasi
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    // Method untuk login
    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Regenerate session untuk keamanan
            request()->session()->regenerate();

            // Redirect ke halaman dashboard
            return $this->redirect('/dashboard');
        }

        // Jika login gagal, tambahkan error
        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
