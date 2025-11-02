<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Password;

#[Layout('layouts.guest')] 
#[Title('Lupa Password')]
class ForgotPassword extends Component
{
    public $email = '';
    public $status = '';

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.required' => 'Oops, you forgot to enter your email!',
        'email.email' => 'Hmm… that doesn’t look like a valid email.',
        'email.exists' => 'We couldn’t find an account with that email — mind checking it again?',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = 'We just sent you a password reset link — check your email!';
            $this->email = '';
        } else {
            $this->addError('email', 'Oops! Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
