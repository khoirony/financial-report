<?php

namespace App\Livewire\Investment;

use Livewire\Component;

class FireCalculator extends Component
{
    public float $currentBalance = 65000000;
    public float $monthlySavings = 3000000;
    public float $annualBonus = 9000000;
    public float $annualGrowthRate = 10;
    public float $monthlySpending = 6000000;
    public float $retirementGrowthRate = 10;
    public float $postRetirementMonthlySavings = 2000000;
    public float $inflationRate = 3;
    public int $currentAge = 25;

    public ?array $results = null;

    public array $chartData = [
        'labels' => [],
        'savings' => [],
        'returns' => [],
        'retirementYear' => null,
        'retirementBalance' => null,
    ];

    protected $rules = [
        'currentBalance' => 'required|numeric|min:0',
        'monthlySavings' => 'required|numeric|min:0',
        'annualBonus' => 'required|numeric|min:0',
        'annualGrowthRate' => 'required|numeric|min:0|max:100',
        'monthlySpending' => 'required|numeric|min:0',
        'retirementGrowthRate' => 'required|numeric|min:0|max:100',
        'postRetirementMonthlySavings' => 'required|numeric|min:0',
        'inflationRate' => 'required|numeric|min:0|max:100',
        'currentAge' => 'required|integer|min:18|max:100',
    ];

    public function mount()
    {
        $this->calculate();
    }

    // ### PERUBAHAN HANYA DI DALAM FUNGSI INI ###
    public function calculate()
    {
        $this->validate();

        $targetNestEgg = $this->calculateTargetNestEgg();
        
        $balance = $this->currentBalance;
        $totalSavings = $this->currentBalance;
        $retirementAge = null;
        $retirementBalance = null;
        $retirementYearLabel = null;
        $currentYear = date('Y');

        $labels = [];
        $savingsData = [];
        $returnsData = [];
        $yearsAfterRetirement = 0;

        for ($year = 0; $year <= 50; $year++) {
            $age = $this->currentAge + $year;
            $yearLabel = $currentYear + $year;

            if ($retirementAge) {
                $yearsAfterRetirement++;
                if ($yearsAfterRetirement > 5) {
                    break;
                }

                $labels[] = $yearLabel;
                $interest = $balance * ($this->retirementGrowthRate / 100);
                $postContribution = $this->postRetirementMonthlySavings * 12;

                $balance += $interest + $postContribution;
                $totalSavings += $postContribution;
                
                $savingsData[] = $totalSavings;
                $returnsData[] = $balance - $totalSavings;
            } 
            else {
                $labels[] = $yearLabel;
                $savingsData[] = $totalSavings;
                $returnsData[] = $balance - $totalSavings;

                $interest = $balance * ($this->annualGrowthRate / 100);
                $contribution = ($this->monthlySavings * 12) + $this->annualBonus;

                $balance += $interest + $contribution;
                $totalSavings += $contribution;

                $inflatedTarget = $targetNestEgg * pow(1 + ($this->inflationRate / 100), $year + 1);

                if ($balance >= $inflatedTarget) {
                    $retirementAge = $age;
                    $retirementBalance = $balance;
                    $retirementYearLabel = $yearLabel;
                }
            }
        }

        if ($retirementAge) {
            $this->results = [
                'retirementAge' => $retirementAge,
                'retirementBalance' => $retirementBalance,
                'retirementDate' => date('F Y', strtotime("+$retirementAge years -$this->currentAge years")),
            ];
        } else {
            $this->results = null;
        }

        $this->chartData = [
            'labels' => $labels,
            'savings' => $savingsData,
            'returns' => $returnsData,
            'retirementYear' => $retirementYearLabel,
            'retirementBalance' => $retirementBalance,
        ];

        $this->dispatch('chartUpdated', $this->chartData);
    }

    private function calculateTargetNestEgg(): float
    {
        $realReturnRate = $this->retirementGrowthRate - $this->inflationRate;
        if ($realReturnRate <= 0) {
            return 999999999999.0; 
        }
        $annualSpending = $this->monthlySpending * 12;
        return $annualSpending / ($realReturnRate / 100);
    }

    public function render()
    {
        return view('livewire.investment.fire-calculator');
    }
}
