<?php

namespace App\Livewire;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TestAlert extends Component
{
    use LivewireAlert;

    public function showAlert()
    {
        $this->alert('success', 'Test Alert!', [
            'position' => 'top-end',
        ]);
    }

    public function render()
    {
        return view('livewire.test-alert');
    }
}