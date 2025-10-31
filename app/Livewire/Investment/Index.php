<?php

namespace App\Livewire\Investment;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use App\Models\Investment;
use App\Models\InvestmentCategory;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use LivewireAlert, WithFileUploads, WithPagination;

    public $investments;

    public $categories;

    public $filterCategory = '';

    public function mount()
    {
        $this->categories = InvestmentCategory::all();
    }

    public function formatAmount($id)
    {
        $amount = $this->investments[$id]['amount'];

        // Hilangkan nol di belakang koma
        $this->investments[$id]['amount'] = rtrim(rtrim($amount, '0'), ',');
    }

    public function delete($id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        $this->alert('success', 'Investment successfully deleted!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function updatedInvestments($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->investments[$index]['id'] ?? null;

        if ($field === 'amount') {
            $value = preg_replace('/[^\d]/', '', $value);
        }

        Investment::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function addNewInvestment()
    {
        $newInvestment = Investment::create([
            'user_id' => Auth::user()->id,
            'cashflow_category_id' => 1,
            'description' => '',
            'amount' => 0,
            'transaction_date' => now(),
        ]);

        $this->investments[] = $newInvestment->toArray();
    }

    public function render()
    {
        $this->investments = Investment::with('investmentCode.category')->where('user_id', Auth::user()->id)
            ->when($this->filterCategory, function ($query) {
                $query->where('investment_code_id.investment_category_id', $this->filterCategory);
            })
            ->get()->keyBy('id')->toArray();

        return view('livewire.investment.index');
    }
}
