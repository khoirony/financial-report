<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10 flex justify-between">
            <h1 class="text-3xl font-semibold">Manage Cashflow</h1>
            <button class="rounded-lg px-4 py-2 border border-quick-silver">Add New Cashflow</button>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Transactions</h2>
                <div class="relative">
                    <select x-model="transactionFilter" @change="filterTransactions" class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head>Date</x-table.head>
                            <x-table.head>Description</x-table.head>
                            <x-table.head>Category</x-table.head>
                            <x-table.head>Amount</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($cashflows as $id => $cashflow)
                            <x-table.row>
                                <x-table.data>
                                    <input type="date" wire:model.lazy="cashflows.{{ $id }}.transaction_date" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="cashflows.{{ $id }}.description" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cashflow['type_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                        <select wire:model.lazy="cashflows.{{ $id }}.category_id" class="rounded-full border-none ring-0 text-sm font-light {{ $cashflow['type_id'] === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </x-table.data>
                                <x-table.data>
                                    <input
                                        type="text"
                                        x-data="{ amount: '{{ number_format($cashflow['amount'], 0, ',', '.') }}' }"
                                        x-init="
                                            format();
                                            function format() {
                                                amount = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount.replace(/[^\d]/g, ''));
                                            }
                                            $el.value = amount;
                                            $el.addEventListener('input', (e) => {
                                                amount = e.target.value;
                                                format();
                                                $el.value = amount;
                                            });
                                        "
                                        x-on:blur="$wire.set('cashflows.{{ $id }}.amount', amount.replace(/[^\d]/g, ''))"
                                        class="rounded border-none ring-0 text-sm font-light"
                                    />
                                </x-table.data>
                                <x-table.data class="text-center">
                                    <button wire:click="delete({{ $cashflow['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </x-table.data>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.data colspan="5">
                                    <div class="text-center">No Data Found</div>
                                </x-table.data>
                            </x-table.row>
                        @endforelse
                    </x-table.body>
                </x-table.table>

                {{-- {{ $cashflows->links() }} --}}
            </div>
        </div>
    </main>
</div>
