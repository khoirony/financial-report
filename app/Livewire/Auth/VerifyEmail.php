<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.guest')] 
#[Title('Verify Email')]
class VerifyEmail extends Component
{
    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
