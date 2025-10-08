<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center bg-gradient-to-tr from-emerald-500 via-teal-400 to-green-300 overflow-hidden"
>

    <!-- Background Floating Shapes -->
    <div class="absolute inset-0">
        <div class="absolute -top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 bg-emerald-200/30 rounded-full blur-3xl animate-[spin_12s_linear_infinite]"></div>
    </div>

    <!-- Register Card -->
    <div 
        x-show="show" 
        x-transition.opacity.duration.700ms 
        class="relative z-10 bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl w-[90%] sm:w-[400px]"
    >
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-emerald-700">Create Account</h1>
            <p class="text-slate-500 text-sm mt-1">Start managing your money smarter</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-5">
            <div>
                <label class="text-sm text-slate-600 font-medium">Full Name</label>
                <input wire:model="name" type="text" class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Email</label>
                <input wire:model="email" type="email" class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Password</label>
                <input wire:model="password" type="password" class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" class="mt-1 w-full px-4 py-2 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50">
            </div>

            <button
                class="w-full py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-emerald-600 to-teal-500 hover:shadow-lg transform hover:scale-[1.03] transition-all duration-300">
                Sign Up
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-700">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition">Login</a>
        </div>
    </div>
</div>
