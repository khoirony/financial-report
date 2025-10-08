<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

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
