<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10">
            <h1 class="text-xl md:text-3xl font-semibold">Manage Investment</h1>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Investment Categories</h2>
    
                <div class="relative">
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head class="w-10">id</x-table.head>
                            <x-table.head>Name</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($categories as $id => $category)
                            <x-table.row wire:key="category-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="categories.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $category['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
    
                {{-- {{ $category->links() }} --}}
            </div>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All Investment Codes</h2>
    
                <div class="relative">
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head class="w-10">id</x-table.head>
                            <x-table.head>Name</x-table.head>
                            <x-table.head>Type</x-table.head>
                            <x-table.head>Code</x-table.head>
                            <x-table.head>Source</x-table.head>
                            <x-table.head>Currency</x-table.head>
                            <x-table.head>Unit</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($codes as $id => $code)
                            <x-table.row wire:key="code-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="codes.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <select wire:model.lazy="codes.{{ $id }}.investment_category_id" class="rounded-full border-none ring-0 text-sm font-light px-3 {{ $this->getCategoryColor($code['investment_category_id'] ?? null) }}">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                        @endforeach
                                    </select>
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="codes.{{ $id }}.investment_code" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="codes.{{ $id }}.source" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="codes.{{ $id }}.currency" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="codes.{{ $id }}.unit" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $code['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
    
                {{-- {{ $category->links() }} --}}
            </div>
        </div>
    </main>
</div>
