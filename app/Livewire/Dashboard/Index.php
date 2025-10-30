<?php

namespace App\Livewire\Dashboard;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use App\Models\Investment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

class Index extends Component
{
    public $categories;

    public $cashflows;

    public $investments;

    public $investmenentsTotal;

    public $investmenentCurrentPriceTotal;

    public $investmenentBuyingPriceTotal;

    public $investmentChange;

    public $income;

    public $incomeChange;

    public $expenses;

    public $expenseChange;

    public $expenseChartLabels;

    public $expenseChartData;

    public $expenseMonthLabels;

    public $expenseMonthData;

    public $filterCategory = '';
    
    public $filterPieMonthYear;

    public function mount()
    {
        $this->loadSummaryData();
        $this->categories = CashflowCategory::all();
        $this->filterPieMonthYear = $this->filterPieMonthYear ?? now()->format('Y-m');

        // Chart Pie Expense by Category
        [$year, $month] = explode('-', $this->filterPieMonthYear);

        $expenseByCategory = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->whereHas('category', fn($q) => $q->where('cashflow_type_id', 2))
            ->get()
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));

        $this->expenseChartLabels = $expenseByCategory->keys();
        $this->expenseChartData = $expenseByCategory->values();

        // Chart Line Expense by Month
        $expenseByMonth = Cashflow::where('user_id', Auth::user()->id)
        ->whereHas('category', fn($q) => $q->where('cashflow_type_id', 2)) // hanya pengeluaran
        ->selectRaw('DATE_FORMAT(transaction_date, "%Y-%m") as ym, SUM(amount) as total')
        ->groupBy('ym')
        ->orderBy('ym')
        ->get();

        $this->expenseMonthLabels = $expenseByMonth->map(function ($item) {
            return \Carbon\Carbon::createFromFormat('Y-m', $item->ym)->translatedFormat('M Y');
        });

        $this->expenseMonthData = $expenseByMonth->pluck('total');
    }

    public function loadSummaryData()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

        // Cashflow bulan ini
        $this->cashflows = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->when($this->filterCategory, function ($query) {
                $query->where('cashflow_category_id', $this->filterCategory);
            })
            ->orderByDesc('transaction_date')
            ->get();

        // Cashflow bulan kemarin (untuk perbandingan)
        $lastCashflows = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('transaction_date', $lastMonth)
            ->whereYear('transaction_date', $lastMonthYear)
            ->get();

        $lastIncome = $lastCashflows->where('cashflow_category_id', 1)->sum('amount');
        $lastExpenses = $lastCashflows->where('category.cashflow_type_id', 2)->sum('amount');

        $this->income = $this->cashflows->where('cashflow_category_id', 1)->sum('amount');
        $this->expenses = $this->cashflows->where('category.cashflow_type_id', 2)->sum('amount');

        // Investments
        $this->investments = Investment::where('user_id', Auth::user()->id)->get();
        $this->investmenentCurrentPriceTotal = $this->investments->sum(fn($inv) => $inv->current_price * $inv->amount);
        $this->investmenentBuyingPriceTotal = $this->investments->sum(fn($inv) => $inv->average_buying_price * $inv->amount);

        // Hitung persentase perubahan
        $this->incomeChange = $lastIncome > 0 
            ? round((($this->income - $lastIncome) / $lastIncome) * 100, 1)
            : null;

        $this->expenseChange = $lastExpenses > 0 
            ? round((($this->expenses - $lastExpenses) / $lastExpenses) * 100, 1)
            : null;

        $this->investmentChange = $this->investmenentBuyingPriceTotal > 0 
            ? round((($this->investmenentCurrentPriceTotal - $this->investmenentBuyingPriceTotal) / $this->investmenentBuyingPriceTotal) * 100, 1)
            : null;
    }

    public function updatedFilterPieMonthYear()
    {
        // Jalankan ulang logika agar data terupdate
        [$year, $month] = explode('-', $this->filterPieMonthYear);

        $expenseByCategory = Cashflow::with('category')
            ->where('user_id', Auth::user()->id)
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->whereHas('category', fn($q) => $q->where('cashflow_type_id', 2))
            ->get()
            ->groupBy('category.name')
            ->map(fn($items) => $items->sum('amount'));

        $this->expenseChartLabels = $expenseByCategory->keys();
        $this->expenseChartData = $expenseByCategory->values();

        if (!empty($this->expenseChartLabels) && !empty($this->expenseChartData)) {
            $this->dispatch('update-doughnut-chart', [
                'labels' => $this->expenseChartLabels,
                'data' => $this->expenseChartData,
            ]);
        } else {
            $this->dispatch('update-doughnut-chart', [
                'labels' => [],
                'data' => [],
            ]);
        }
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
