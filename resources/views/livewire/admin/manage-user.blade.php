<div>
    <main class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-10">
            <h1 class="text-3xl font-semibold">Manage Users</h1>
        </div>

        <div class="bg-white rounded-lg border border-bright-gray overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">All User Roles</h2>
    
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
                        @forelse ($roles as $id => $role)
                            <x-table.row wire:key="role-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="roles.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $role['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
                <h2 class="text-lg font-semibold text-gray-900">All Registered Users</h2>
    
                <div class="relative">
                </div>
            </div>
            <div class="overflow-x-auto">
                <x-table.table>
                    <x-table.header>
                        <x-table.row>
                            <x-table.head class="w-10">id</x-table.head>
                            <x-table.head>Name</x-table.head>
                            <x-table.head>Role</x-table.head>
                            <x-table.head>Email</x-table.head>
                            <x-table.head>Email Verified</x-table.head>
                            <x-table.head>Created At</x-table.head>
                            <x-table.head :centered="'true'">Actions</x-table.head>
                        </x-table.row>
                    </x-table.header>
                    <x-table.body>
                        @forelse ($users as $id => $user)
                            <x-table.row wire:key="user-{{ $id }}">
                                <x-table.data class="text-center">
                                    {{ $id }}
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="users.{{ $id }}.name" class="rounded border-none ring-0 text-sm font-light">
                                </x-table.data>
                                <x-table.data>
                                    <select wire:model.lazy="users.{{ $id }}.role_id" class="rounded-full border-none ring-0 text-sm font-light pl-5 pr-10 {{ $this->getRoleColor($user['role_id'] ?? null) }}">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                </x-table.data>
                                <x-table.data>
                                    <input type="text" wire:model.lazy="users.{{ $id }}.email" class="rounded border-none ring-0 text-sm font-light w-full">
                                </x-table.data>
                                <x-table.data>
                                    {{ \Carbon\Carbon::parse($user['email_verified_at'])->format('H:i:s d-M-Y') }}
                                </x-table.data>
                                <x-table.data>
                                    {{ \Carbon\Carbon::parse($user['created_at'])->format('H:i:s d-M-Y') }}
                                </x-table.data>
                                <x-table.data class="text-center w-40">
                                    <button wire:click="delete({{ $user['id'] }})" style="cursor: pointer;" class="text-red-600 hover:text-red-900">
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
