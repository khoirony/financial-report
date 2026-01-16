<div x-data="fireCalculator()">
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">FIRE Calculator</h1>
            <p class="text-gray-500 mt-1">Simulasi perjalanan menuju kebebasan finansial Anda.</p>
        </div>

        <div class="w-full grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="col-span-1">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-6">
                    
                    <div>
                        <h2 class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-4">Aset & Akumulasi</h2>
                        <div class="space-y-4">
                            <div x-data="moneyInput(@entangle('currentBalance').live)">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Investasi Saat Ini</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="text" x-model="displayValue" @input="update($event)" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div x-data="moneyInput(@entangle('monthlySavings').live)">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Investasi Bulanan (Rutin)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="text" x-model="displayValue" @input="update($event)" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div x-data="moneyInput(@entangle('annualBonus').live)">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Tahunan / THR</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="text" x-model="displayValue" @input="update($event)" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Return Investasi Tahunan (%)</label>
                                <div class="relative">
                                    <input type="number" wire:model.live.debounce.500ms="annualGrowthRate" step="0.1" class="w-full pl-4 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <span class="absolute right-3 top-2 text-gray-500">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <h2 class="text-xs uppercase tracking-wide text-gray-500 font-bold mb-4">Target Pengeluaran</h2>
                        <div class="space-y-4">
                            <div x-data="moneyInput(@entangle('monthlySpending').live)">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Hidup Bulanan (Sekarang)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="text" x-model="displayValue" @input="update($event)" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div x-data="moneyInput(@entangle('postRetirementIncome').live)">
                                <label class="block text-sm font-medium text-green-700 mb-1">Pemasukan Saat Pensiun (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-green-600">+Rp</span>
                                    <input type="text" x-model="displayValue" @input="update($event)" class="w-full pl-12 pr-4 py-2 border border-green-300 bg-green-50 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="0">
                                </div>
                                <p class="text-[11px] text-gray-500 mt-1">Isi jika Anda berencana tetap bekerja ringan/bisnis (Barista FIRE).</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Inflasi (%)</label>
                                    <div class="relative">
                                        <input type="number" wire:model.live.debounce.500ms="inflationRate" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="absolute right-2 top-2 text-xs text-gray-400">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Umur Sekarang</label>
                                    <input type="number" wire:model.live.debounce.500ms="currentAge" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                </div>
                            </div>
                            
                             <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Return Saat Pensiun (%)</label>
                                <div class="relative">
                                    <input type="number" wire:model.live.debounce.500ms="retirementGrowthRate" step="0.1" class="w-full px-3 py-2 border border-gray-200 bg-gray-50 rounded-lg text-sm text-gray-600">
                                    <span class="absolute right-3 top-2 text-xs text-gray-400">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1 xl:col-span-2 space-y-6">
                
                @if ($results)
                    <div class="bg-gradient-to-br from-blue-700 to-indigo-900 rounded-xl p-6 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5 blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 rounded-full bg-blue-400 opacity-10 blur-2xl pointer-events-none"></div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center relative z-10">
                            
                            <div class="text-center lg:text-left border-b lg:border-b-0 lg:border-r border-blue-500/30 pb-6 lg:pb-0 lg:pr-8">
                                <div class="text-blue-200 text-xs font-bold uppercase tracking-widest mb-2">Target Finansial</div>
                                <div class="text-6xl font-extrabold text-white tracking-tight">
                                    {{ $results['retirementAge'] }} <span class="text-2xl font-medium text-blue-300">tahun</span>
                                </div>
                                <div class="text-blue-200 text-sm mt-3 flex items-center justify-center lg:justify-start gap-2 bg-blue-800/30 py-1 px-3 rounded-full w-fit mx-auto lg:mx-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Tercapai pada tahun <strong>{{ $results['retirementDate'] }}</strong></span>
                                </div>
                            </div>
                            
                            <div class="col-span-1 lg:col-span-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                                    
                                    <div class="relative pl-4 border-l-2 border-green-400">
                                        <div class="text-blue-200 text-[11px] uppercase font-bold tracking-wide mb-1">Total Saldo Terkumpul</div>
                                        <div class="text-2xl font-bold text-white tracking-tight">
                                            Rp{{ number_format($results['retirementBalance'], 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-blue-300 mt-1">Aset likuid saat pensiun</div>
                                    </div>
                    
                                    <div class="relative pl-4 border-l-2 border-blue-400">
                                        <div class="text-blue-200 text-[11px] uppercase font-bold tracking-wide mb-1">Investasi Anda Pertahun</div>
                                        <div class="text-xl font-bold text-white">
                                            Rp{{ number_format(($monthlySavings * 12) + $annualBonus, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-blue-300 mt-1">Modal yang disetor (Sekarang)</div>
                                    </div>
                    
                                    <div class="col-span-1 sm:col-span-2 border-t border-blue-500/30 my-2"></div>
                    
                                    <div>
                                        <div class="text-blue-200 text-[11px] uppercase font-bold tracking-wide mb-1">Biaya Hidup Masa Depan</div>
                                        <div class="text-lg font-semibold text-white">
                                            Rp{{ number_format($results['monthlySpendingFuture'], 0, ',', '.') }}<span class="text-xs font-normal opacity-70">/bln</span>
                                        </div>
                                        <div class="text-xs text-red-300 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                            Efek Inflasi {{ $inflationRate }}%
                                        </div>
                                    </div>
                    
                                    <div>
                                        <div class="text-blue-200 text-[11px] uppercase font-bold tracking-wide mb-1">Gaji Pensiun (Barista FIRE)</div>
                                        <div class="text-lg font-semibold text-green-300">
                                            +Rp{{ number_format($results['monthlyIncomeFuture'], 0, ',', '.') }}<span class="text-xs font-normal opacity-70">/bln</span>
                                        </div>
                                        <div class="text-xs text-blue-300 mt-1">Penghasilan sampingan nanti</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-1 sm:col-span-3 bg-blue-900/40 rounded-lg p-3 border border-blue-500/30 flex justify-between items-center">
                                <div>
                                    <div class="text-blue-200 text-[10px] uppercase font-bold">Kekurangan (Ditutup Investasi)</div>
                                    <div class="text-xl font-bold text-yellow-300">
                                        Rp{{ number_format($results['gapToCover'], 0, ',', '.') }}<span class="text-sm font-normal text-white">/bulan</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-blue-200 text-[10px] uppercase font-bold">Asumsi Return</div>
                                    <div class="text-sm font-medium">{{ $retirementGrowthRate }}% (Saat Pensiun)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Target Belum Tercapai</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Dalam 60 tahun ke depan, saldo investasi belum cukup menutup biaya hidup. Coba tingkatkan investasi atau tambah pemasukan pensiun.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div wire:ignore class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm h-[500px] relative">
                    <canvas id="fireChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fireCalculator', () => ({
                init() {
                    this.initChart();
                    
                    Livewire.on('update-chart', (event) => {
                        const data = event.data || event[0]?.data || event;
                        if (data) this.updateChart(data);
                    });
                },

                initChart() {
                    const ctx = document.getElementById('fireChart');
                    if (!ctx) return;

                    const existingChart = Chart.getChart(ctx);
                    if (existingChart) existingChart.destroy();

                    const initialData = @json($chartData);

                    new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: initialData.labels,
                            datasets: [
                                // DATASET 1: Modal Pokok (Stacked)
                                {
                                    label: 'Total Tabungan',
                                    data: initialData.savings,
                                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                    borderColor: 'rgba(99, 102, 241, 0.8)',
                                    borderWidth: 2,
                                    fill: true,
                                    pointRadius: 0,
                                    yAxisID: 'y', // Sumbu Kiri
                                    order: 2
                                },
                                // DATASET 2: Bunga/Return (Stacked)
                                {
                                    label: 'Total Return Investasi',
                                    data: initialData.returns,
                                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                                    borderColor: 'rgba(34, 197, 94, 1)',
                                    borderWidth: 2,
                                    fill: true,
                                    pointRadius: 0,
                                    yAxisID: 'y', // Sumbu Kiri
                                    order: 3
                                },
                                // DATASET 3: Garis Pengeluaran (Sumbu Kanan)
                                {
                                    label: 'Biaya Hidup/Thn',
                                    data: initialData.expenses,
                                    borderColor: 'rgba(239, 68, 68, 0.8)', // Merah
                                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                    borderWidth: 2,
                                    borderDash: [5, 5],
                                    fill: false,
                                    pointRadius: 0,
                                    yAxisID: 'y1', // Sumbu Kanan
                                    order: 1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            animation: { duration: 500 },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('id-ID', {
                                                    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
                                                }).format(context.parsed.y);
                                            }
                                            return label;
                                        }
                                    }
                                },
                                legend: { position: 'top' }
                            },
                            scales: {
                                // SUMBU KIRI (Miliaran - Saldo)
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    stacked: true,
                                    title: { display: true, text: 'Total Aset' },
                                    ticks: {
                                        callback: function(value) {
                                            if(value >= 1000000000) return (value/1000000000).toFixed(1) + ' M';
                                            if(value >= 1000000) return (value/1000000).toFixed(0) + ' Jt';
                                            return value;
                                        }
                                    }
                                },
                                // SUMBU KANAN (Ratusan Juta - Pengeluaran)
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    stacked: false,
                                    grid: { drawOnChartArea: false }, // Hapus grid kanan agar rapi
                                    title: { display: true, text: 'Biaya Hidup', color: '#ef4444' },
                                    ticks: {
                                        color: '#ef4444',
                                        callback: function(value) {
                                            if(value >= 1000000000) return (value/1000000000).toFixed(1) + ' M';
                                            if(value >= 1000000) return (value/1000000).toFixed(0) + ' Jt';
                                            return value;
                                        }
                                    }
                                },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                },

                updateChart(data) {
                    const chart = Chart.getChart('fireChart');
                    if (chart) {
                        chart.data.labels = data.labels;
                        chart.data.datasets[0].data = data.savings;
                        chart.data.datasets[1].data = data.returns;
                        // Update dataset pengeluaran
                        if(chart.data.datasets[2]) {
                            chart.data.datasets[2].data = data.expenses;
                        }
                        chart.update('none');
                    } else {
                        this.initChart();
                    }
                }
            }));

            // Komponen Helper Input Uang
            Alpine.data('moneyInput', (model) => ({
                value: model,
                displayValue: '',
                init() {
                    this.displayValue = this.format(this.value);
                    this.$watch('value', (newValue) => {
                        if (this.clean(this.displayValue) !== this.clean(newValue)) {
                            this.displayValue = this.format(newValue);
                        }
                    });
                },
                update(event) {
                    let input = event.target.value;
                    let number = this.clean(input);
                    this.value = number;
                    this.displayValue = this.format(number);
                },
                format(val) {
                    if (val === '' || val === null) return '';
                    return new Intl.NumberFormat('id-ID').format(val);
                },
                clean(val) {
                    return val.toString().replace(/\D/g, '');
                }
            }));
        });
    </script>
</div>