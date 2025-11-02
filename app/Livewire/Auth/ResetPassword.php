<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request; 

#[Layout('layouts.guest')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;
    public $error = '';

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'password.required' => 'Oops, you forgot to enter a new password!',
        'password.min' => 'Your password needs to be at least 8 characters long.',
        'password.confirmed' => 'The password confirmation doesn’t match — try again!',
    ];    

    public function mount(Request $request, $token)
    {
        $this->token = $token;
        $this->email = $request->input('email', '');
    }

    public function resetPassword()
    {
        $this->error = '';
        $this->validate();

        $status = Password::reset([
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ], function ($user, $password) {
            $user->password = bcrypt($password);
            $user->setRememberToken(Str::random(60));
            $user->save();
            
            auth()->login($user);
        });

        if ($status === Password::PASSWORD_RESET) {
            return $this->redirect('/dashboard', navigate: true);
        }

        if ($status === Password::INVALID_TOKEN) {
            $this->error = 'Oops! This reset link isn’t valid anymore or has expired.';
        } else {
            $this->error = 'Couldn’t reset your password. Please try again.';
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
