<?php

namespace App\Livewire\Investment;

use Livewire\Component;

class FireCalculator extends Component
{
    // --- Properti Input ---
    public $currentBalance = 65000000;
    public $monthlySavings = 3000000;
    public $annualBonus = 9000000;
    public $monthlySpending = 6000000;
    public $postRetirementIncome = 0; // Income tambahan saat pensiun (Barista FIRE)

    public float $annualGrowthRate = 10;     // Return investasi masa muda
    public float $retirementGrowthRate = 6;  // Return saat pensiun (lebih aman)
    public float $inflationRate = 3;         // Inflasi tahunan
    public int $currentAge = 25;

    public ?array $results = null;

    // --- Data Chart ---
    public array $chartData = [
        'labels' => [],
        'savings' => [],  // Uang Pokok
        'returns' => [],  // Bunga / Growth
        'expenses' => [], // Garis Pengeluaran (Dataset baru)
    ];

    protected $rules = [
        'currentBalance' => 'required',
        'monthlySavings' => 'required',
        'annualBonus' => 'required',
        'monthlySpending' => 'required',
        'postRetirementIncome' => 'required',
        'annualGrowthRate' => 'required|numeric|min:0|max:100',
        'retirementGrowthRate' => 'required|numeric|min:0|max:100',
        'inflationRate' => 'required|numeric|min:0|max:100',
        'currentAge' => 'required|integer|min:18|max:90',
    ];

    public function mount()
    {
        $this->calculate();
    }

    public function updated()
    {
        $this->calculate();
    }

    public function calculate()
    {
        // 1. Sanitasi Input (Hapus titik ribuan)
        $currentBalance = (float) str_replace('.', '', $this->currentBalance);
        $monthlySavings = (float) str_replace('.', '', $this->monthlySavings);
        $annualBonus = (float) str_replace('.', '', $this->annualBonus);
        $monthlySpending = (float) str_replace('.', '', $this->monthlySpending);
        $postRetirementIncome = (float) str_replace('.', '', $this->postRetirementIncome);

        if ($monthlySpending <= 0) {
            $this->results = null;
            return;
        }

        $balance = $currentBalance;
        $totalPrincipal = $currentBalance;
        
        $retirementAge = null;
        $retirementBalance = null;
        $retirementYearLabel = null;
        $currentYear = (int) date('Y');

        $labels = [];
        $savingsData = [];
        $returnsData = [];
        $expensesData = []; // Array untuk garis merah (pengeluaran)
        
        $hasRetired = false;

        // 2. Hitung Target Dana Pensiun
        // Kita hanya perlu investasi untuk menutup GAP (Biaya Hidup - Gaji Pensiun)
        $monthlyGap = max(0, $monthlySpending - $postRetirementIncome);
        $realRetirementRate = $this->retirementGrowthRate - $this->inflationRate;
        
        // Rumus Perpetuity / 4% Rule yang disesuaikan
        $initialTargetNestEgg = ($realRetirementRate > 0) 
            ? ($monthlyGap * 12) / ($realRetirementRate / 100)
            : ($monthlyGap * 12) * 25;

        if ($monthlyGap <= 0) $initialTargetNestEgg = 0;

        // 3. Loop Simulasi 60 Tahun
        for ($year = 0; $year <= 60; $year++) {
            $yearLabel = $currentYear + $year;
            $myAge = $this->currentAge + $year;

            // Hitung nilai masa depan akibat inflasi
            $inflationFactor = pow(1 + ($this->inflationRate / 100), $year);
            
            $inflatedAnnualSpending = ($monthlySpending * 12) * $inflationFactor;
            $inflatedAnnualIncome = ($postRetirementIncome * 12) * $inflationFactor;
            
            // Target dana pensiun tahun ini (ikut naik kena inflasi)
            $targetForThisYear = $initialTargetNestEgg * $inflationFactor;

            if (!$hasRetired) {
                // --- FASE AKUMULASI ---
                
                // Cek apakah sudah pensiun?
                if ($balance >= $targetForThisYear && $year >= 0) {
                    $hasRetired = true;
                    $retirementAge = $myAge;
                    $retirementBalance = $balance;
                    $retirementYearLabel = $yearLabel;
                    
                    $this->results = [
                        'retirementAge' => $retirementAge,
                        'retirementBalance' => $retirementBalance,
                        'retirementDate' => $yearLabel,
                        'monthlySpendingFuture' => $inflatedAnnualSpending / 12,
                        'monthlyIncomeFuture' => $inflatedAnnualIncome / 12,
                        'gapToCover' => max(0, ($inflatedAnnualSpending - $inflatedAnnualIncome) / 12),
                    ];
                }
            }

            $labels[] = $yearLabel;
            
            if ($hasRetired) {
                // --- FASE PENSIUN (DRAWDOWN) ---
                
                // Tambah Return Investasi
                $investmentReturn = $balance * ($this->retirementGrowthRate / 100);
                
                // Hitung Kekurangan (Net Withdrawal)
                $netWithdrawal = $inflatedAnnualSpending - $inflatedAnnualIncome;

                if ($netWithdrawal > 0) {
                    // Tarik dari tabungan
                    $balance = $balance + $investmentReturn - $netWithdrawal;
                } else {
                    // Surplus (Gaji Pensiun > Biaya Hidup), tabung sisanya
                    $surplus = abs($netWithdrawal);
                    $balance = $balance + $investmentReturn + $surplus;
                    $totalPrincipal += $surplus; 
                }
                
                if ($balance < 0) $balance = 0;

            } else {
                // --- FASE INVESTASI ---
                $investmentReturn = $balance * ($this->annualGrowthRate / 100);
                $contribution = ($monthlySavings * 12) + $annualBonus;
                
                $balance += $investmentReturn + $contribution;
                $totalPrincipal += $contribution;
            }

            // Simpan Data Chart
            $savingsData[] = $totalPrincipal; 
            $returnsData[] = max(0, $balance - $totalPrincipal); // Agar bisa distack
            $expensesData[] = $inflatedAnnualSpending; // Simpan pengeluaran tahunan
        }

        // Fallback jika target tercapai instan
        if (!$hasRetired && $initialTargetNestEgg == 0) {
             $this->results = [
                'retirementAge' => $this->currentAge,
                'retirementBalance' => $currentBalance,
                'retirementDate' => $currentYear,
                'monthlySpendingFuture' => $monthlySpending,
                'monthlyIncomeFuture' => $postRetirementIncome,
                'gapToCover' => 0
            ];
        } elseif (!$hasRetired) {
            $this->results = null;
        }

        $this->chartData = [
            'labels' => $labels,
            'savings' => $savingsData,
            'returns' => $returnsData,
            'expenses' => $expensesData, // Kirim ke view
        ];
        
        $this->dispatch('update-chart', data: $this->chartData); 
    }

    public function render()
    {
        return view('livewire.investment.fire-calculator');
    }
}