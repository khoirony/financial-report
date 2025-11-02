<?php

namespace App\Livewire\Admin;

use App\Models\CashflowCategory;
use App\Models\CashflowType;
use Livewire\Component;

class ManageCashflow extends Component
{
    public $categories;
    public $types;

    public function updatedCategories($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->categories[$index]['id'] ?? null;

        CashflowCategory::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function updatedTypes($value, $key)
    {
        [$index, $field] = explode('.', $key);
        $id = $this->types[$index]['id'] ?? null;

        CashflowType::where('id', $id)->update([
            $field => $value,
        ]);
    }

    public function render()
    {
        $this->categories = CashflowCategory::orderBy('id')
            ->get()->keyBy('id')->toArray();
        $this->types = CashflowType::orderBy('id')
            ->get()->keyBy('id')->toArray();

        return view('livewire.admin.manage-cashflow');
    }
}
