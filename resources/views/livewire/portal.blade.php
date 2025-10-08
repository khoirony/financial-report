<div 
    x-data="{ isRegister: @entangle('isRegister'), animate: false }"
    x-init="$watch('isRegister', () => { animate = true; setTimeout(() => animate = false, 700) })"
    class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-400 to-teal-500"
>

    <!-- Background Decor -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-emerald-200/30 rounded-full blur-3xl animate-[spin_20s_linear_infinite]"></div>
    </div>

    <!-- Sliding Container -->
    <div 
        class="relative w-[90%] sm:w-[400px] overflow-hidden bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl z-10"
    >
        <!-- Inner Container (for slide effect) -->
        <div 
            class="flex transition-transform duration-700 ease-in-out"
            :class="isRegister ? '-translate-x-1/2' : 'translate-x-0'"
            style="width: 200%;"
        >

            <!-- LOGIN FORM -->
            <div class="w-1/2 p-8">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-extrabold text-emerald-700">Welcome Back</h1>
                    <p class="text-slate-500 text-sm mt-1">Login to manage your finances</p>
                </div>

                <form wire:submit.prevent="login" class="space-y-4">
                    <div>
                        <label class="text-sm text-slate-600">Email</label>
                        <input wire:model="email" type="email"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Password</label>
                        <input wire:model="password" type="password"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center gap-2">
                            <input wire:model="remember" type="checkbox" class="rounded text-emerald-600">
                            Remember me
                        </label>
                        <a href="#" class="text-emerald-600 hover:underline">Forgot?</a>
                    </div>

                    <button
                        class="w-full py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-emerald-600 to-teal-500 hover:shadow-lg transform hover:scale-[1.02] transition-all duration-300">
                        Sign In
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-slate-700">
                    Don’t have an account?
                    <button @click="isRegister = true" class="text-emerald-600 font-semibold hover:text-emerald-800 transition">
                        Create One
                    </button>
                </div>
            </div>

            <!-- REGISTER FORM -->
            <div class="w-1/2 p-8">
                <div class="text-center mb-6">
                    <h1 class="text-3xl font-extrabold text-emerald-700">Create Account</h1>
                    <p class="text-slate-500 text-sm mt-1">Start managing smarter</p>
                </div>

                <form wire:submit.prevent="register" class="space-y-4">
                    <div>
                        <label class="text-sm text-slate-600">Name</label>
                        <input wire:model="name" type="text"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Email</label>
                        <input wire:model="emailReg" type="email"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @error('emailReg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Password</label>
                        <input wire:model="passwordReg" type="password"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                        @error('passwordReg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm text-slate-600">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password"
                            class="w-full mt-1 px-4 py-2 border border-emerald-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-emerald-400 focus:outline-none transition-all">
                    </div>

                    <button
                        class="w-full py-2.5 rounded-xl text-white font-semibold bg-gradient-to-r from-emerald-600 to-teal-500 hover:shadow-lg transform hover:scale-[1.03] transition-all duration-300">
                        Register
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-slate-700">
                    Already have an account?
                    <button @click="isRegister = false" class="text-emerald-600 font-semibold hover:text-emerald-800 transition">
                        Login
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
