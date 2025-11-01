<?php

namespace App\Livewire\Auth;

use App\Models\User;
// TAMBAHKAN baris 'use' ini
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Register')]
class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    // Aturan validasi (sudah benar)
    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    // Method untuk melakukan registrasi
    public function register()
    {
        // Jalankan validasi
        $this->validate();

        // Buat user baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => 2,
        ]);

        // TAMBAHKAN baris ini untuk memicu pengiriman email
        event(new Registered($user));

        // Tetap loginkan user
        auth()->login($user);

        // Redirect ke dashboard (middleware 'verified' akan menangani sisanya)
        return $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
