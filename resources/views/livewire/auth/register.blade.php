<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden md:bg-[#F6F2EA]"
>

    <!-- Background Floating Shapes -->
    <div class="absolute inset-0">
        <div class="absolute -top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 md:bg-emerald-200/30 rounded-full blur-3xl animate-[spin_12s_linear_infinite]"></div>
    </div>

    <!-- Register Card -->
    <div 
        x-show="show" 
        x-transition.opacity.duration.700ms 
        class="relative z-10 bg-white md:backdrop-blur-xl md:shadow-2xl rounded-3xl px-8 py-12 my-5 w-full max-w-[500px] md:border border-white/20"
    >
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-emerald-700">Create New Account</h1>
            <p class="text-slate-500 text-sm mt-1">Start managing your money smarter</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-5">
            <div>
                <label class="text-sm text-slate-600 font-medium">Full Name</label>
                <input wire:model="name" type="text" placeholder="Type your full name here.."
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50 placeholder:text-gray-400 placeholder:text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Email</label>
                <input wire:model="email" type="email" placeholder="email@example.com"
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50 placeholder:text-gray-400 placeholder:text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Password</label>
                <input wire:model="password" type="password" placeholder="Type your password here.."
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50 placeholder:text-gray-400 placeholder:text-sm">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-slate-600 font-medium">Confirm Password</label>
                <input wire:model="password_confirmation" type="password" placeholder="Type again your password here.."
                    class="mt-1 w-full px-4 py-3 border border-emerald-200 rounded-xl focus:ring-2 focus:ring-emerald-400 bg-white/50 placeholder:text-gray-400 placeholder:text-sm">
            </div>

            <button
                class="w-full py-2.5 rounded-xl text-gray-800 font-semibold bg-[#FDC886] hover:shadow-lg transform hover:scale-[1.03] transition-all duration-300">
                Sign Up
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-700">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-800 transition">Login</a>
        </div>
    </div>
</div>
