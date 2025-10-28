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
                <svg width="80px" height="80px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <style>.cls-1{fill:#ffeabb;}.cls-2{fill:#eae2f9;}.cls-3{fill:#c0c3ee;}.cls-4{fill:#a2a5de;}.cls-5{fill:#f7bf75;}.cls-6{fill:#9293a5;}.cls-7,.cls-9{fill:#6f7188;}.cls-8{fill:#f1734d;}.cls-8,.cls-9{fill-rule:evenodd;}</style>
                    </defs>
                    <g id="Transaction">
                        <path class="cls-1" d="M14.21,7.16V19h7.23a.68.68,0,0,0,.68-.68V7.85a.66.66,0,0,0-.2-.48.68.68,0,0,0-.48-.21Z"/>
                        <path class="cls-2" d="M3.87,3.48A1.75,1.75,0,0,0,2.12,5.23V19.74a.35.35,0,0,0,.21.32A.34.34,0,0,0,2.7,20l.6-.53a.5.5,0,0,1,.67,0l.76.68a.63.63,0,0,0,.83,0l.76-.68a.5.5,0,0,1,.67,0l.77.68a.61.61,0,0,0,.82,0l.76-.68a.5.5,0,0,1,.67,0l.76.68a.61.61,0,0,0,.82,0l.77-.68a.5.5,0,0,1,.67,0l.6.53a.34.34,0,0,0,.37.06.35.35,0,0,0,.21-.32V4.55a1.07,1.07,0,0,1,1.07-1.07Zm11.41,0-.19.65h1.18a1.09,1.09,0,0,0-1-.65"/>
                        <path class="cls-3" d="M12.36,3.48v16h0a.5.5,0,0,1,.67,0l.6.53a.34.34,0,0,0,.37.06.35.35,0,0,0,.21-.32V4.55a1.07,1.07,0,0,1,1.07-1.07Zm2.92,0-.19.65h1.18a1.09,1.09,0,0,0-1-.65"/>
                        <path class="cls-4" d="M16.35,7.16H14.21V4.55a1.07,1.07,0,1,1,2.14,0Z"/>
                        <path class="cls-5" d="M20.28,7.16V19h1.16a.66.66,0,0,0,.48-.2.67.67,0,0,0,.2-.48V7.85a.66.66,0,0,0-.2-.48.67.67,0,0,0-.48-.21Z"/>
                        <rect class="cls-6" height="1.93" width="7.92" x="14.21" y="9.16"/>
                        <rect class="cls-7" height="1.93" width="1.84" x="20.28" y="9.16"/>
                        <path class="cls-8" d="M19,14v-.11a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5V14a1.22,1.22,0,0,1,.38.26,1.31,1.31,0,0,1,.26.4.5.5,0,0,1-.26.65.51.51,0,0,1-.66-.26.22.22,0,0,0-.05-.08.21.21,0,0,0-.17-.07.25.25,0,1,0,0,.49,1.24,1.24,0,0,1,.5,2.38v.12a.51.51,0,0,1-.5.5.5.5,0,0,1-.5-.5v-.12a1.2,1.2,0,0,1-.65-.65.51.51,0,0,1,.26-.66.5.5,0,0,1,.66.27.22.22,0,0,0,.05.08.29.29,0,0,0,.18.07.25.25,0,0,0,0-.49A1.25,1.25,0,0,1,19,14Z"/>
                        <path class="cls-8" d="M7.67,6.28V6.14a.5.5,0,0,1,.5-.5.51.51,0,0,1,.5.5v.15a1.39,1.39,0,0,1,.41.27A1.34,1.34,0,0,1,9.36,7a.5.5,0,0,1-.92.39.36.36,0,0,0-.07-.1.28.28,0,0,0-.2-.08.29.29,0,0,0-.3.29.3.3,0,0,0,.3.3A1.29,1.29,0,0,1,9.46,9.07a1.31,1.31,0,0,1-.79,1.2v.14a.5.5,0,0,1-.5.5.5.5,0,0,1-.5-.5v-.14A1.34,1.34,0,0,1,7.25,10h0A1.34,1.34,0,0,1,7,9.57a.5.5,0,0,1,.92-.38A.35.35,0,0,0,8,9.28a.34.34,0,0,0,.22.09.3.3,0,0,0,0-.59,1.3,1.3,0,0,1-.5-2.5Z"/>
                        <path class="cls-9" d="M4.35,12.9H6.11a.51.51,0,0,0,.5-.5.5.5,0,0,0-.5-.5H4.35a.5.5,0,0,0-.5.5A.5.5,0,0,0,4.35,12.9Z"/>
                        <path class="cls-9" d="M8,12.9h4a.5.5,0,0,0,.5-.5.5.5,0,0,0-.5-.5H8a.5.5,0,0,0-.5.5A.51.51,0,0,0,8,12.9Z"/>
                        <path class="cls-9" d="M12,13.93H10.2a.5.5,0,0,0-.5.5.51.51,0,0,0,.5.5H12a.5.5,0,0,0,.5-.5A.5.5,0,0,0,12,13.93Z"/>
                        <path class="cls-9" d="M8.34,13.93h-4a.5.5,0,0,0-.5.5.5.5,0,0,0,.5.5h4a.51.51,0,0,0,.5-.5A.5.5,0,0,0,8.34,13.93Z"/>
                        <path class="cls-9" d="M4.35,17H6.11a.51.51,0,0,0,.5-.5.5.5,0,0,0-.5-.5H4.35a.5.5,0,0,0-.5.5A.5.5,0,0,0,4.35,17Z"/>
                        <path class="cls-9" d="M8,17h4a.5.5,0,0,0,.5-.5A.5.5,0,0,0,12,16H8a.5.5,0,0,0-.5.5A.51.51,0,0,0,8,17Z"/>
                    </g>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-emerald-700">Financial Report</h1>
            <p class="text-slate-600 text-sm mt-1">Smart. Simple. Insightful.</p>
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
