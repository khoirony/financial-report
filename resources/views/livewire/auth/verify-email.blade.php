<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden w-full"
>
    <!-- Register Card -->
    <div 
        x-show="show" 
        x-transition.opacity.duration.700ms 
        class="relative z-10 bg-white rounded-3xl px-8 py-12 my-5 w-full max-w-[500px] md:border"
    >
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            Verify Your Email Address
        </h2>
        
        <p class="text-gray-600 mb-12">
            Thanks for signing up! Before you continue, please check your email and click the verification link we sent you.
        </p>
        
        <p class="text-gray-600 mb-6">
            Didn’t get the email? No worries — we can send it again.
        </p>

        <!-- Menampilkan pesan sukses jika email berhasil dikirim ulang -->
        @if (session('message'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex items-center justify-center gap-4">
            <!-- Tombol Kirim Ulang Email -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type"submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Resend Verification Email
                </button>
            </form>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}"> <!-- Asumsi Anda punya rute 'logout' -->
                @csrf
                <button type="submit" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
