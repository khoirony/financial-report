<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden w-full"
>
    <!-- Reset Password Card -->
    <div 
        x-show="show" 
        x-transition.opacity.duration.700ms 
        class="relative z-10 bg-white rounded-3xl px-8 py-12 w-full max-w-[500px] md:border"
    >
        <div class="max-w-md w-full">
            
            <h2 class="text-2xl font-bold text-gray-800 mb-12 text-center">
                Set Your New Password
            </h2>

            <!-- Menampilkan pesan error (jika token salah, dll) -->
            @if ($error)
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-md">
                    {{ $error }}
                </div>
            @endif

            <form wire:submit.prevent="resetPassword" class="text-left space-y-4">
                
                <!-- Input Token (Hidden) -->
                <input wire:model="token" type="hidden">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input wire:model="email" type="email" placeholder="email@example.com"
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 placeholder:text-gray-400 placeholder:text-sm">
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700">New Password</label>
                    <input wire:model="password" type="password" placeholder="Type your password here.."
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 placeholder:text-gray-400 placeholder:text-sm">
                    @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Confirmation Password</label>
                    <input wire:model="password_confirmation" type="password" placeholder="Type again your password here.."
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 placeholder:text-gray-400 placeholder:text-sm">
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full flex justify-center items-center">
                        <span wire:loading.remove wire:target="resetPassword">
                            Reset Password
                        </span>
                        <span wire:loading wire:target="resetPassword">
                            Processing...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
