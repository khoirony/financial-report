<?php

namespace App\Livewire\Investment;

use Livewire\Component;
use App\Models\BrokerSummary;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BrokerStats extends Component
{
    use LivewireAlert;

    public $search = '';
    public $startDate;
    public $endDate;
    
    public $sortBy = 'date';
    public $sortDir = 'desc';

    public function mount()
    {
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->startDate = Carbon::today()->subDays(7)->format('Y-m-d');
    }

    public function syncData()
    {
        $this->validate([
            'search'    => 'required|string',
            'startDate' => 'required|date',
            'endDate'   => 'required|date|after_or_equal:startDate',
        ]);

        $ticker = $this->search;
        $start  = Carbon::parse($this->startDate);
        $end    = Carbon::parse($this->endDate);

        if ($start->diffInDays($end) > 7) {
            session()->flash('message', '⚠️ Rentang tanggal terlalu lebar. Mohon pilih max 7 hari agar tidak timeout.');
            return;
        }

        $countSuccess = 0;
        $currentDate = $start->copy();

        while ($currentDate <= $end) {
            if (! $currentDate->isWeekend()) {
                
                $dateStr = $currentDate->format('Y-m-d');

                Artisan::call('scrape:broker-sum', [
                    'ticker' => $ticker,
                    '--date' => $dateStr
                ]);

                $countSuccess++;
            }

            // Lanjut ke hari berikutnya
            $currentDate->addDay();
        }

        $this->alert('success', 'Sinkronisasi '.$ticker.' selesai! ('.$countSuccess.' hari kerja diproses)', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'desc';
        }
    }

    public function render()
    {
        $query = BrokerSummary::query();

        // Filter Logic
        if ($this->search) {
            $searchTerm = strtoupper($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('ticker', 'like', '%' . $searchTerm . '%')
                  ->orWhere('broker_code', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        // Ambil Data
        $data = $query->orderBy($this->sortBy, $this->sortDir)->get();

        // --- HITUNG TOTAL (Untuk ditampilkan di Header) ---
        $totalBuyVal = $data->sum('buy_val');
        $totalSellVal = $data->sum('sell_val');
        $totalNetVol = $data->sum('net_vol');
        
        // (Optional) Net Value dalam Rupiah (Buy Val - Sell Val)
        // $totalNetValue = $totalBuyVal - $totalSellVal; 

        return view('livewire.investment.broker-stats', [
            'summaries' => $data,
            'totalBuyVal' => $totalBuyVal,
            'totalSellVal' => $totalSellVal,
            'totalNetVol' => $totalNetVol,
        ]);
    }
}