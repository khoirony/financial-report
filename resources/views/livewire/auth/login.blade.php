<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-400 to-teal-500"
>

    <!-- Background Animation -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-64 h-64 bg-emerald-300/30 rounded-full blur-3xl animate-[spin_10s_linear_infinite]"></div>
    </div>

    <!-- Floating Card -->
    <div 
        x-show="show" 
        x-transition.scale.origin.center.duration.700ms 
        class="relative z-10 bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl p-8 w-[90%] sm:w-[400px] border border-white/20"
    >
        <div class="text-center mb-8">
            <div class="flex justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-emerald-600 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-2.21 0-4 1.79-4 4h2a2 2 0 1 1 4 0h2a4 4 0 0 0-4-4zM5 12h2m10 0h2m-7 4v2m0-10V6" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-emerald-700">FinanceFlow</h1>
            <p class="text-slate-600 text-sm mt-1">Smart. Simple. Secure.</p>
        </div>

        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <label class="text-sm text-slate-600 font-medium">Email</label>
                <input wire:model="email" type="email"
                    class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all duration-300 bg-white/50">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Password</label>
                <input wire:model="password" type="password"
                    class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all duration-300 bg-white/50">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-between text-sm items-center">
                <label class="flex items-center gap-2">
                    <input wire:model="remember" type="checkbox" class="text-emerald-600 rounded">
                    Remember me
                </label>
                <a href="#" class="text-emerald-600 hover:text-emerald-800 transition">Forgot?</a>
            </div>

            <button 
                class="w-full py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-emerald-600 to-teal-500 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-700">
            New here? 
            <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition">Create Account</a>
        </div>
    </div>
</div>
