<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10">
            <h1 class="text-3xl font-semibold">Dashboard Test</h1>
        </div>
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Income Card -->
            <div class="rounded-lg p-6 border border-bright-gray text-quick-silver space-y-5">
                <div class="flex items-center gap-5">
                    <i class="text-xl fas fa-sack-dollar"></i>
                    <p class="text-lg font-medium">Total Income</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-semibold text-gray-900">Rp {{ number_format($income, 0, ',', '.') }},-</p>
                    <div class="text-xl flex items-center gap-3">
                        @if ($incomeChange < 0)
                            <i class="fas fa-arrow-trend-down"></i>
                        @else
                            <i class="fas fa-arrow-trend-up"></i>
                        @endif
                        <p>{{ $incomeChange }}%</p>
                    </div>
                </div>
            </div>

            <!-- Expenses Card -->
            <div class="rounded-lg p-6 border border-bright-gray text-quick-silver space-y-5">
                <div class="flex items-center gap-5">
                    <i class="text-xl fas fa-money-bill-transfer"></i>
                    <p class="text-lg font-medium">Total Expenses</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-semibold text-gray-900">Rp {{ number_format($expenses, 0, ',', '.') }},-</p>
                    <div class="text-xl flex items-center gap-3">
                        @if ($expenseChange < 0)
                            <i class="fas fa-arrow-trend-down"></i>
                        @else
                            <i class="fas fa-arrow-trend-up"></i>
                        @endif
                        <p>{{ $expenseChange }}%</p>
                    </div>
                </div>
            </div>

            <!-- Savings Card -->
            <div class="rounded-lg p-6 border border-bright-gray text-quick-silver space-y-5">
                <div class="flex items-center gap-5">
                    <i class="text-xl fas fa-piggy-bank"></i>
                    <p class="text-lg font-medium">Total Equity</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-3xl font-semibold text-gray-900">Rp {{ number_format($investmenentCurrentPriceTotal, 0, ',', '.') }},-</p>
                    <div class="text-xl flex items-center gap-3">
                        @if ($investmentChange < 0)
                            <i class="fas fa-arrow-trend-down"></i>
                        @else
                            <i class="fas fa-arrow-trend-up"></i>
                        @endif
                        <p>{{ $investmentChange }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Expenses Breakdown -->
            <div class="bg-white rounded-lg border border-bright-gray p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Expenses Breakdown</h2>
                    <div class="mb-4">
                        <select id="filterPieMonthYear" wire:model.live="filterPieMonthYear" class="border border-gray-300 rounded-lg px-3 py-2 w-auto">
                            @foreach(range(0, 11) as $i)
                                @php
                                    $date = now()->subMonths($i);
                                @endphp
                                <option value="{{ $date->format('Y-m') }}">
                                    {{ $date->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="h-64" wire:ignore x-init="renderDoughnutChart(@json($expenseChartLabels), @json($expenseChartData))">
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-white rounded-lg border border-bright-gray p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Monthly Trend</h2>
                <div class="h-64" wire:ignore x-init="renderLineChart(@json($expenseMonthLabels), @json($expenseMonthData))">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Insights Section -->
        <div class="bg-white rounded-lg border border-bright-gray p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Spending Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Overspending -->
                <div class="border border-red-100 bg-red-50 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-full bg-red-100 text-red-600 mr-3">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <h3 class="font-medium text-red-800">Potential Overspending</h3>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-3">These categories exceeded your budget:</p>
                        <ul class="space-y-2">
                            <li class="flex justify-between items-center">
                                <span class="text-sm font-medium">Entertainment</span>
                                <span class="text-sm font-medium text-red-600">Rp 300.000 over</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-sm font-medium">Belanja Online</span>
                                <span class="text-sm font-medium text-red-600">Rp 200.000 over</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Savings Opportunity -->
                <div class="border border-green-100 bg-green-50 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="font-medium text-green-800">Savings Opportunity</h3>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-3">You could save more by reducing these expenses:</p>
                        <ul class="space-y-2">
                            <li class="flex justify-between items-center">
                                <span class="text-sm font-medium">Shopeefood</span>
                                <span class="text-sm font-medium text-green-600">Rp 250.000 spent</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-sm font-medium">Gofood</span>
                                <span class="text-sm font-medium text-green-600">Rp 150.000 spent</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Latest Transactions</h2>
                <div class="relative">
                    <select wire:model.lazy="filterCategory" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="">All Transactions</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($cashflows as $id => $cashflow)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <p class="text-sm font-light">{{ $cashflow->transaction_date }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <p class="text-sm font-light">{{ $cashflow->description }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashflow->type_id === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $cashflow->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                    <p class="text-sm font-light">Rp {{ number_format($cashflow->amount, 0, ',', '.') }},-</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="flex items-center justify-center py-5">
                                    <div>No Data Found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- {{ $cashflows->links() }} --}}
            </div>
        </div>
    </main>
    
    <script>
        let myDoughnutChart;
        let myLineChart;
    
        document.addEventListener('livewire:navigated', () => {
            // Render chart saat halaman pertama kali dimuat
            renderDoughnutChart(@json($expenseChartLabels), @json($expenseChartData));
            renderLineChart(@json($expenseMonthLabels), @json($expenseMonthData));
        });
    
        // 🔥 Event listener dari Livewire v3
        document.addEventListener('livewire:init', () => {
            Livewire.on('update-doughnut-chart', (data) => {
                renderDoughnutChart(data[0].labels, data[0].data);
            });
        });
    
        function renderDoughnutChart(labels, data) {
            const ctx = document.getElementById('doughnutChart').getContext('2d');
    
            if (myDoughnutChart) {
                myDoughnutChart.destroy();
            }
    
            myDoughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: data,
                        backgroundColor: [
                            '#f87171', '#60a5fa', '#facc15',
                            '#34d399', '#a78bfa', '#f472b6'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }
    
        function renderLineChart(labels, data) {
            const lineCtx = document.getElementById('lineChart').getContext('2d');
    
            if (myLineChart) {
                myLineChart.destroy();
            }
    
            myLineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: data,
                        fill: true,
                        borderColor: '#4ade80',
                        backgroundColor: 'rgba(74, 222, 128, 0.2)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    </script>
      
</div>
