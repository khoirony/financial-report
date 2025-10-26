<?php

namespace App\Livewire\Cashflow;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    public $cashflows;

    public $categories;

    public $filterCategory = '';

    public $file;

    public function mount()
    {
        $this->categories = CashflowCategory::all();
    }

    public function delete($id)
    {
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
            $field => $value,
        ]);
    }

    public function addNewCashflow()
    {
        $newCashflow = Cashflow::create([
            'user_id' => Auth::user()->id,
            'cashflow_category_id' => 1,
            'description' => '',
            'amount' => 0,
            'transaction_date' => now(),
        ]);

        $this->cashflows[] = $newCashflow->toArray();
    }

    public function render()
    {
        $this->cashflows = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->when($this->filterCategory, function ($query) {
                $query->where('cashflow_category_id', $this->filterCategory);
            })
            ->orderByDesc('transaction_date')
            ->get()->keyBy('id')->toArray();

        return view('livewire.cashflow.index');
    }
}
