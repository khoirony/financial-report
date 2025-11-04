<?php

namespace App\Livewire\Investment;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class FireCalculator extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    public function render()
    {
        return view('livewire.investment.fire-calculator');
    }
}
