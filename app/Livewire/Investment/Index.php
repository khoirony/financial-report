<?php

namespace App\Livewire\Investment;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\InvestmentCode;
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

    public $investmentCodes;

    public $filterCategory = '';

    public function mount()
    {
        $this->categories = InvestmentCategory::all();
        $this->investmentCodes = InvestmentCode::all();
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
            $cleaned_value = str_replace('.', '', $value); 
        
            $cleaned_value = str_replace(',', '.', $cleaned_value);

            if (is_numeric($cleaned_value)) {
                $value = $cleaned_value;
            } else {
                $value = 0; 
            }
        }

        Investment::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function addNewInvestment()
    {
        $newInvestment = Investment::create([
            'user_id' => Auth::user()->id,
            'investment_code_id' => null,
            'average_buying_price' => 0,
            'amount' => 0,
            'broker' => '',
        ]);

        $this->investments[] = $newInvestment->toArray();
    }

    public function getPnL($buyingPrice, $currentPrice, $unit, $amount)
    {
        $buyingTotalValue = $this->getPrice($buyingPrice, $unit)*$amount;
        $currentTotalValue = $this->getPrice($currentPrice, $unit)*$amount;

        return $buyingTotalValue > 0 ? round((($currentTotalValue - $buyingTotalValue) / $buyingTotalValue) * 100, 1) : 0; 
    }

    public function getTotalValue($price, $unit, $amount)
    {
        return $this->getPrice($price, $unit)*$amount;
    }

    public function getPrice($price, $unit)
    {
        if($unit == 'lot') {
            return $price*100;
        } else {
            return $price;
        }
    }

    public function getCategoryColor($categoryId)
    {
        if($categoryId == InvestmentCategory::STOCK) {
            return 'bg-green-100 text-green-800';
        }elseif($categoryId == InvestmentCategory::INDEX) {
            return 'bg-blue-100 text-blue-800';
        }elseif($categoryId == InvestmentCategory::CRYPTO) {
            return 'bg-orange-100 text-orange-800';
        }elseif($categoryId == InvestmentCategory::GOLD) {
            return 'bg-yellow-100 text-yellow-800';
        } else {
            return 'bg-gray-100 text-gray-800';
        }
    }

    public function render()
    {
        $this->investments = Investment::with(['investmentCode.category', 'latestMarketPrice'])
            ->leftJoin('investment_codes', 'investment_codes.id', '=', 'investments.investment_code_id')
            ->where('investments.user_id', Auth::user()->id) 
            ->when($this->filterCategory, function ($query) {
                $query->where('investment_codes.investment_category_id', $this->filterCategory);
            })
            ->orderBy('investment_codes.investment_category_id')
            ->select('investments.*') 
            ->get()
            ->keyBy('id')
            ->toArray();

        return view('livewire.investment.index');
    }
}
