<?php

namespace App\Livewire\Admin;

use App\Models\InvestmentCategory;
use App\Models\InvestmentCode;
use Livewire\Component;

class ManageInvestment extends Component
{
    public $categories;
    public $codes;

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

    public function updatedCategories($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->categories[$index]['id'] ?? null;

        InvestmentCategory::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function updatedCodes($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->codes[$index]['id'] ?? null;

        InvestmentCode::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function render()
    {
        $this->categories = InvestmentCategory::orderBy('id')
            ->get()->keyBy('id')->toArray();
        $this->codes = InvestmentCode::orderBy('id')
            ->get()->keyBy('id')->toArray();

        return view('livewire.admin.manage-investment');
    }
}
