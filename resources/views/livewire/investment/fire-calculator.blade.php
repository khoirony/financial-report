<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10 flex justify-between">
            <h1 class="text-xl md:text-3xl font-semibold">Fire Calculator</h1>
        </div>

        <div class="w-full grid grid-cols-1 xl:grid-cols-3 gap-5">
            <div class="col-span-1">
                <form wire:submit.prevent="calculate" 
                    class="bg-white p-6 rounded-lg border border-gray-200 space-y-4"
                    x-data="{
                        currentBalance: @js(number_format($currentBalance, 0, ',', '.')),
                        monthlySavings: @js(number_format($monthlySavings, 0, ',', '.')),
                        annualBonus: @js(number_format($annualBonus, 0, ',', '.')),
                        monthlySpending: @js(number_format($monthlySpending, 0, ',', '.')),
                        postRetirementMonthlySavings: @js(number_format($postRetirementMonthlySavings, 0, ',', '.'))
                    }"
                    x-init="() => {
                        @this.set('currentBalance', cleanNumber(currentBalance));
                        @this.set('monthlySavings', cleanNumber(monthlySavings));
                        @this.set('annualBonus', cleanNumber(annualBonus));
                        @this.set('monthlySpending', cleanNumber(monthlySpending));
                        @this.set('postRetirementMonthlySavings', cleanNumber(postRetirementMonthlySavings));
                    }"
                >
                    <h2 class="text-xl font-semibold text-gray-800">Net Worth Projection</h2>
                    
                    @php
                    $inputWrapperClass = "relative";
                    $inputClass = "block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-8 pr-10";
                    $symbolClass = "absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-gray-500";
                    $percentClass = "absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-500";
                    @endphp

                    <div>
                        <label for="currentBalance" class="block text-sm font-medium text-gray-700">Current Total Investment</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <span class="{{ $symbolClass }}">Rp</span>
                            <input type="text" 
                                inputmode="numeric" 
                                id="currentBalance" 
                                class="{{ $inputClass }}"
                                x-model="currentBalance"
                                @input="currentBalance = formatNumber($event.target.value); @this.set('currentBalance', cleanNumber($event.target.value))">
                        </div>
                    </div>

                    <div>
                        <label for="monthlySavings" class="block text-sm font-medium text-gray-700">Monthly Investment</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <span class="{{ $symbolClass }}">Rp</span>
                            <input type="text" 
                                inputmode="numeric" 
                                id="monthlySavings" 
                                class="{{ $inputClass }}"
                                x-model="monthlySavings"
                                @input="monthlySavings = formatNumber($event.target.value); @this.set('monthlySavings', cleanNumber($event.target.value))">
                        </div>
                    </div>

                    <div>
                        <label for="annualBonus" class="block text-sm font-medium text-gray-700">Annual Bonus / THR (optional)</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <span class="{{ $symbolClass }}">Rp</span>
                            <input type="text" 
                                inputmode="numeric" 
                                id="annualBonus" 
                                class="{{ $inputClass }}"
                                x-model="annualBonus"
                                @input="annualBonus = formatNumber($event.target.value); @this.set('annualBonus', cleanNumber($event.target.value))">
                        </div>
                    </div>

                    <div>
                        <label for="annualGrowthRate" class="block text-sm font-medium text-gray-700">Estimated Annual Profit</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <input type="number" step="0.1" wire:model.defer="annualGrowthRate" id="annualGrowthRate" class="{{ $inputClass }} !pl-3">
                            <span class="{{ $percentClass }}">%</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Retirement Calculation</h3>
                    
                    <div>
                        <label for="monthlySpending" class="block text-sm font-medium text-gray-700">Estimated Monthly Spending</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <span class="{{ $symbolClass }}">Rp</span>
                            <input type="text" 
                                inputmode="numeric" 
                                id="monthlySpending" 
                                class="{{ $inputClass }}"
                                x-model="monthlySpending"
                                @input="monthlySpending = formatNumber($event.target.value); @this.set('monthlySpending', cleanNumber($event.target.value))">
                        </div>
                    </div>
                    <div>
                        <label for="retirementGrowthRate" class="block text-sm font-medium text-gray-700">Post-Retirement Growth Rate</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <input type="number" step="0.1" wire:model.defer="retirementGrowthRate" id="retirementGrowthRate" class="{{ $inputClass }} !pl-3">
                            <span class="{{ $percentClass }}">%</span>
                        </div>
                    </div>
                    <div>
                        <label for="postRetirementMonthlySavings" class="block text-sm font-medium text-gray-700">Monthly Investment After Retirement (optional)</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <span class="{{ $symbolClass }}">Rp</span>
                            <input type="text" 
                                inputmode="numeric" 
                                id="postRetirementMonthlySavings" 
                                class="{{ $inputClass }}"
                                x-model="postRetirementMonthlySavings"
                                @input="postRetirementMonthlySavings = formatNumber($event.target.value); @this.set('postRetirementMonthlySavings', cleanNumber($event.target.value))">
                        </div>
                    </div>
                    <div>
                        <label for="inflationRate" class="block text-sm font-medium text-gray-700">Estimated Inflation</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <input type="number" step="0.1" wire:model.defer="inflationRate" id="inflationRate" class="{{ $inputClass }} !pl-3">
                            <span class="{{ $percentClass }}">%</span>
                        </div>
                    </div>
                    <div>
                        <label for="currentAge" class="block text-sm font-medium text-gray-700">Your Current Age</label>
                        <div class="{{ $inputWrapperClass }} mt-1">
                            <input type="number" wire:model.defer="currentAge" id="currentAge" class="{{ $inputClass }} !pl-3">
                            <span class="{{ $percentClass }}">thn</span>
                        </div>
                    </div>
    
                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span wire:loading.remove>Calculate</span>
                        <span wire:loading>Calculating...</span>
                    </button>
                </form>
            </div>
            <div class="col-span-2 space-y-5">
                <div class="bg-white p-4 md:p-6 rounded-lg border border-gray-200">
                    <div class="w-full h-[500px]" wire:ignore>
                        <canvas id="retirementChartCanvas"></canvas>
                    </div>
                </div>
    
                @if ($results)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 bg-blue-600 text-white p-6 rounded-lg flex flex-col justify-center items-center text-center">
                            <span class="text-sm font-light">You Will Retire at</span>
                            <span class="text-3xl md:text-4xl font-bold">age {{ $results['retirementAge'] }}</span>
                        </div>
                        
                        <div class="md:col-span-2 bg-white p-6 rounded-lg border border-gray-200 grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-sm text-gray-500">Current Investment</span>
                                <span class="block text-lg font-semibold text-gray-900">Rp{{ number_format($currentBalance, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">Monthly Investment</span>
                                <span class="block text-lg font-semibold text-gray-900">Rp{{ number_format($monthlySavings, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">Retirement Balance</span>
                                <span class="block text-lg font-semibold text-gray-900">Rp{{ number_format($results['retirementBalance'], 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">Annual Investment Pre Retirement</span>
                                <span class="block text-lg font-semibold text-gray-900">Rp{{ number_format(($monthlySavings * 12) + $annualBonus, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">Retire On</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ $results['retirementDate'] }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500">Annual Profitability Rate</span>
                                <span class="block text-lg font-semibold text-gray-900">{{ $annualGrowthRate }}%</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <h3 class="text-lg font-semibold text-yellow-600">Cannot Calculate Retirement</h3>
                        <p class="text-gray-600 mt-2">Based on the current numbers, you cannot retire within the next 50 years. Try increasing your savings or reducing your spending expectations.</p>
                    </div>
                @endif
            </div>
        </div>
        <div x-data={lang:'en'} class="w-full mt-10 bg-white p-6 md:p-8 rounded-lg border border-gray-200 space-y-6">
            <div class="space-y-3">
                <div class="flex gap-5">
                    <h2 class="text-2xl font-semibold text-gray-900">
                        <span x-show="lang === 'en'">What is FIRE?</span>
                        <span x-show="lang === 'id'">Apa Itu FIRE?</span>
                    </h2>
                    <button @click="lang = (lang === 'en' ? 'id' : 'en')" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                        <span x-show="lang === 'en'">Versi Indonesia</span>
                        <span x-show="lang === 'id'">English Version</span>
                    </button>
                </div>
                <p class="text-gray-700" x-show="lang === 'en'">
                    FIRE stands for <strong class="font-medium">Financial Independence, Retire Early</strong>. It is a lifestyle movement focused on aggressive savings and investment with the goal of achieving financial independence far earlier than the traditional retirement age.
                </p>
                <p class="text-gray-700" x-show="lang === 'id'">
                    FIRE adalah singkatan dari <strong class="font-medium">Financial Independence, Retire Early</strong> (Kemandirian Finansial, Pensiun Dini). Ini adalah sebuah gerakan gaya hidup yang berfokus pada penghematan dan investasi agresif dengan tujuan untuk mencapai kemandirian finansial jauh lebih awal daripada usia pensiun tradisional.
                </p>
                <p class="text-gray-700" x-show="lang === 'en'">
                    The core concept of FIRE is to reach a point where you have saved enough money that the passive income from your investments (like stocks, bonds, or property) can cover all your living expenses. Once you reach this point, you are technically "financially free" and no longer *need* to work for money. Early retirement is an available option, but the goal is the freedom to choose.
                </p>
                <p class="text-gray-700" x-show="lang === 'id'">
                    Inti dari konsep FIRE adalah mencapai titik di mana Anda telah menabung cukup banyak uang sehingga pendapatan pasif dari investasi Anda (seperti saham, obligasi, atau properti) dapat menutupi seluruh biaya hidup Anda. Setelah Anda mencapai titik ini, Anda secara teknis "bebas secara finansial" dan tidak lagi *perlu* bekerja untuk mendapatkan uang. Pensiun dini adalah pilihan yang tersedia, tetapi tujuannya adalah kebebasan untuk memilih.
                </p>
            </div>

            <hr>

            <div class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <span x-show="lang === 'en'">How to Use This Calculator</span>
                    <span x-show="lang === 'id'">Cara Menggunakan Kalkulator Ini</span>
                </h2>
                <p class="text-gray-700" x-show="lang === 'en'">This calculator helps you project when you can achieve financial independence based on your inputs. Here is an explanation for each field:</p>
                <p class="text-gray-700" x-show="lang === 'id'">Kalkulator ini membantu Anda memproyeksikan kapan Anda dapat mencapai kemandirian finansial berdasarkan masukan Anda. Berikut adalah penjelasan untuk setiap kolom:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <span x-show="lang === 'en'">Net Worth Projection (Accumulation Phase)</span>
                            <span x-show="lang === 'id'">Perkiraan Kekayaan Bersih (Tahap Akumulasi)</span>
                        </h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li><strong x-show="lang === 'en'">Current Balance:</strong><strong x-show="lang === 'id'">Saldo Saat Ini:</strong>
                                <span x-show="lang === 'en'"> The total value of your current investments and savings designated for retirement.</span>
                                <span x-show="lang === 'id'"> Total nilai investasi dan tabungan Anda saat ini yang akan digunakan untuk pensiun.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Monthly Savings:</strong><strong x-show="lang === 'id'">Tabungan Bulanan:</strong>
                                <span x-show="lang === 'en'"> The amount you consistently set aside each month to invest.</span>
                                <span x-show="lang === 'id'"> Jumlah yang Anda sisihkan secara konsisten setiap bulan untuk diinvestasikan.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Annual Bonus (THR):</strong><strong x-show="lang === 'id'">Tabungan THR Tahunan:</strong>
                                <span x-show="lang === 'en'"> Annual bonuses, THR, or other additions you invest once per year.</span>
                                <span x-show="lang === 'id'"> Bonus tahunan, THR, atau tambahan lain yang Anda investasikan satu kali per tahun.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Estimated Annual Growth:</strong><strong x-show="lang === 'id'">Perkiraan Pertumbuhan Tahunan:</strong>
                                <span x-show="lang === 'en'"> The average annual return you expect from your investments *before* you retire. This is typically higher (e.g., 8-10%) as you are in the accumulation phase.</span>
                                <span x-show="lang === 'id'"> Rata-rata keuntungan (return) tahunan yang Anda harapkan dari investasi Anda *sebelum* Anda pensiun. Biasanya ini lebih tinggi (misal: 8-10%) karena Anda masih dalam fase akumulasi.</span>
                            </li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <span x-show="lang === 'en'">Retirement Calculation (Withdrawal Phase)</span>
                            <span x-show="lang === 'id'">Kalkulasi Pensiun (Tahap Penarikan)</span>
                        </h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li><strong x-show="lang === 'en'">Estimated Monthly Spending:</strong><strong x-show="lang === 'id'">Perkiraan Pengeluaran Bulanan:</strong>
                                <span x-show="lang === 'en'"> The key to FIRE. This is the amount of money you need to live on per month *after* you retire (in today's money).</span>
                                <span x-show="lang === 'id'"> Kunci dari FIRE. Ini adalah jumlah uang yang Anda butuhkan untuk hidup per bulan *setelah* Anda pensiun (dalam nilai uang hari ini).</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Post-Retirement Growth Rate:</strong><strong x-show="lang === 'id'">Profitabilitas saat Pensiun:</strong>
                                <span x-show="lang === 'en'"> The average annual return you expect from your investments *after* you retire. This number is usually more conservative (e.g., 5-7%) as your portfolio focuses on safety.</span>
                                <span x-show="lang === 'id'"> Rata-rata keuntungan tahunan yang Anda harapkan dari investasi Anda *setelah* Anda pensiun. Angka ini biasanya lebih konservatif (misal: 5-7%) karena portofolio Anda lebih fokus pada keamanan.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Monthly Savings (Post-Retirement):</strong><strong x-show="lang === 'id'">Tabungan Bulanan (Setelah Pensiun):</strong>
                                <span x-show="lang === 'en'"> If you plan to keep saving/investing after reaching FIRE (e.g., from part-time work), enter it here. Leave at 0 if you plan to stop saving.</span>
                                <span x-show="lang === 'id'"> Jika Anda berencana untuk tetap menabung/investasi setelah mencapai FIRE (misal: dari kerja paruh waktu), masukkan di sini. Biarkan 0 jika Anda berencana berhenti menabung.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Estimated Inflation:</strong><strong x-show="lang === 'id'">Perkiraan Inflasi:</strong>
                                <span x-show="lang === 'en'"> The average annual inflation. This calculator uses it to find the *Real Return Rate* (Growth Rate - Inflation) to determine your retirement target.</span>
                                <span x-show="lang === 'id'"> Rata-rata inflasi tahunan. Kalkulator ini menggunakannya untuk menghitung *Real Return Rate* (Profitabilitas - Inflasi) untuk menentukan target dana pensiun Anda.</span>
                            </li>
                            <li><strong x-show="lang === 'en'">Your Current Age:</strong><strong x-show="lang === 'id'">Usia Anda saat ini:</strong>
                                <span x-show="lang === 'en'"> Used to project your retirement age.</span>
                                <span x-show="lang === 'id'"> Digunakan untuk memproyeksikan usia pensiun Anda.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr>

            <div class="space-y-3">
                <h2 class="text-2xl font-semibold text-gray-900">
                    <span x-show="lang === 'en'">How to Read the Results</span>
                    <span x-show="lang === 'id'">Cara Membaca Hasil</span>
                </h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700">
                    <li x-show="lang === 'en'"><strong>Chart:</strong> The chart shows the projection of your wealth over time.</li>
                    <li x-show="lang === 'id'"><strong>Chart:</strong> Chart menunjukkan proyeksi kekayaan Anda dari waktu ke waktu.</li>
                    <ul class="list-decimal list-inside ml-4 mt-1 space-y-1">
                        <li x-show="lang === 'en'">The <strong>Blue Area</strong> is your "Total Investments," or the total money you contributed (principal).</li>
                        <li x-show="lang === 'id'"><strong>Area Biru</strong> adalah "Total Tabungan" Anda, atau jumlah total uang yang Anda masukkan (modal).</li>
                        <li x-show="lang === 'en'">The <strong>Green Area</strong> is your "Total Return Inventments," or the result of your investments (compounding effect).</li>
                        <li x-show="lang === 'id'"><strong>Area Hijau</strong> adalah "Total Keuntungan", atau hasil dari investasi Anda (compounding effect).</li>
                    </ul>
                    <li x-show="lang === 'en'"><strong>Result Cards:</strong> Show a summary of when you will retire, at what age, and with what total balance.</li>
                    <li x-show="lang === 'id'"><strong>Kartu Hasil:</strong> Menampilkan ringkasan kapan Anda akan pensiun, di usia berapa, dan dengan total saldo berapa.</li>
                </ul>
            </div>

        </div>
    </main>

    <script>
        let myRetirementChart;

        function formatNumber(value) {
            if (!value) return '';
            let cleanValue = value.toString().replace(/\./g, '');
            if (isNaN(cleanValue) || cleanValue === '') return '';
            let num = parseInt(cleanValue, 10);
            return num.toLocaleString('id-ID');
        }

        function cleanNumber(value) {
            if (!value) return 0;
            return parseInt(value.toString().replace(/\./g, ''), 10) || 0;
        }

        document.addEventListener('livewire:navigated', () => {
            renderRetirementChart(
                @json($chartData['labels']), 
                @json($chartData['savings']),
                @json($chartData['returns']), 
                @json($chartData['retirementYear']),
                @json($chartData['retirementBalance'])
            );
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('chartUpdated', (data) => {
                const chartData = data[0]; 
                renderRetirementChart(
                    chartData.labels, 
                    chartData.savings, 
                    chartData.returns, 
                    chartData.retirementYear,
                    chartData.retirementBalance
                );
            });
        });

        function renderRetirementChart(labels, savingsData, returnsData, retirementYear, retirementBalance) {
            const ctx = document.getElementById('retirementChartCanvas').getContext('2d');

            if (myRetirementChart) {
                myRetirementChart.destroy();
            }
            
            let annotations = {}; 
            if (retirementYear) {
                annotations.retirementLine = {
                    type: 'line',
                    scaleID: 'x',
                    value: retirementYear,
                    borderColor: 'orange',
                    borderWidth: 2,
                    borderDash: [6, 6],
                    label: {
                        content: 'Pensiun',
                        enabled: true,
                        position: 'top',
                        backgroundColor: 'orange'
                    }
                };
                
                annotations.retirementPoint = {
                    type: 'point',
                    xValue: retirementYear,
                    yValue: retirementBalance,
                    backgroundColor: 'orange',
                    borderColor: 'darkorange',
                    borderWidth: 2,
                    radius: 6,
                    shadow: true
                };
            }

            myRetirementChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Investments',
                            data: savingsData,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            fill: 'origin', 
                            tension: 0.3,
                            pointRadius: 0,
                        },
                        {
                            label: 'Total Return Investments',
                            data: returnsData,
                            backgroundColor: 'rgba(16, 185, 129, 0.5)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            fill: '-1', 
                            tension: 0.3,
                            pointRadius: 0,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: { 
                            stacked: true,
                            ticks: {
                                callback: (value) => `Rp${(value / 1000000).toFixed(0)}jt`
                            }
                        },
                        x: { stacked: true }
                    },
                    plugins: {
                        annotation: {
                            annotations: annotations
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { 
                                            style: 'currency', 
                                            currency: 'IDR',
                                            minimumFractionDigits: 0 
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                },
            });
        }
    </script>
</div>
