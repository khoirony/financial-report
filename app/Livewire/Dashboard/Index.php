<?php

namespace App\Livewire\Dashboard;

use App\Models\Cashflow;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $categories;

    public $cashflows;

    public $income;

    public $expenses;

    public $expenseChartLabels;

    public $expenseChartData;

    public $filterCategory = '';

    public function mount()
    {
        $this->categories = Category::all();
        $this->cashflows = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->when($this->filterCategory, function ($query) {
                $query->where('category_id', $this->filterCategory);
            })
            ->orderByDesc('transaction_date')
            ->get();

        $this->income = $this->cashflows->where('category.type_id', 1)->sum('amount');
        $this->expenses = $this->cashflows->where('category.type_id', 2)->sum('amount');

        $expenseByCategory = $this->cashflows
            ->where('category.type_id', 2) // hanya pengeluaran
            ->groupBy('category.name') // berdasarkan nama kategori
            ->map(function ($items) {
                return $items->sum('amount');
            });

        $this->expenseChartLabels = $expenseByCategory->keys();     // ['Makanan', 'Barang', ...]
        $this->expenseChartData = $expenseByCategory->values();     // [1000000, 800000, ...]
    }

    public function updatedFilterCategory()
    {
        $this->mount();
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
