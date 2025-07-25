<?php

namespace App\Livewire\Cashflow;

use App\Models\Cashflow;
use App\Models\Category;
use App\Models\FileImport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use LivewireAlert, WithPagination, WithFileUploads;

    public $cashflows;
    public $categories;
    // public $description;

    public $file;
    
    public function mount()
    {
        $this->categories = Category::all();
    }

    public function delete($id)
    {
        $test = FileImport::first();
        $test->delete();
        $cashflow = Cashflow::findOrFail($id);
        $cashflow->delete();

        $this->alert('success', 'Cashflow successfully deleted!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function updatedCashflows($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->cashflows[$index]['id'] ?? null;

        if ($field === 'amount') {
            $value = preg_replace('/[^\d]/', '', $value);
        }

        Cashflow::where('id', $id)->update([
            $field => $value
        ]);
    }

    public function render()
    {
        $this->cashflows = Cashflow::with('category')
                            ->where('user_id', 1)
                            ->orderBy('transaction_date')
                            ->get()->keyBy('id')->toArray(); 
        return view('livewire.cashflow.index');
    }
}
