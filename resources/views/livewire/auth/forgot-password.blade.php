<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden w-full"
>
    <!-- Forgot Password Card -->
    <div 
        x-show="show" 
        x-transition.opacity.duration.700ms 
        class="relative z-10 bg-white rounded-3xl px-8 py-12 w-full max-w-[500px] md:border"
    >
        <div class="max-w-md w-full text-center">
            
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Forgot your password?
            </h2>
            
            <p class="text-gray-600 mb-6 mx-10">
                No worries! Just drop your email below and we’ll send you a link to reset your password.
            </p>

            <!-- Menampilkan pesan sukses -->
            @if ($status)
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $status }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="text-left">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                    <input wire:model="email" type="email" placeholder="email@example.com"
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all duration-300 bg-white/50 placeholder:text-gray-400">
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full flex justify-center items-center">
                        <span wire:loading.remove wire:target="sendResetLink">
                            Send Reset Password Link
                        </span>
                        <span wire:loading wire:target="sendResetLink">
                            Sending...
                        </span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" wire:navigate class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Back to Login
                </a>
            </div>

        </div>
    </div>
</div>
