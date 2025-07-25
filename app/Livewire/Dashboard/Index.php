<?php

namespace App\Livewire\Dashboard;

use App\Models\Cashflow;
use App\Models\Category;
use Livewire\Component;

class Index extends Component
{
    public $categories;
    public $cashflows;
    public $income;
    public $expenses;
    public $expenseChartLabels;
    public $expenseChartData;

    public function mount()
    {
        $this->categories = Category::all();
        $this->cashflows =  Cashflow::with('category')
        ->where('user_id', 1)
        ->orderBy('transaction_date')
        ->get();

        $this->income = $this->cashflows->where('type_id', 1)->sum('amount');
        $this->expenses = $this->cashflows->where('type_id', 2)->sum('amount');

        $expenseByCategory = $this->cashflows
            ->where('type_id', 2) // hanya pengeluaran
            ->groupBy('category.name') // berdasarkan nama kategori
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $this->expenseChartLabels = $expenseByCategory->keys();     // ['Makanan', 'Barang', ...]
        $this->expenseChartData = $expenseByCategory->values();     // [1000000, 800000, ...]
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
