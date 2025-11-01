<div 
    x-data="{ show: false }" 
    x-init="setTimeout(() => show = true, 200)" 
    class="relative min-h-screen flex items-center justify-center overflow-hidden"
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
        class="relative z-10 bg-white rounded-3xl px-8 py-12 w-full max-w-[500px] md:border"
    >
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            Verifikasi Alamat Email Anda
        </h2>

        <p class="text-gray-600 mb-6">
            Terima kasih telah mendaftar! Sebelum melanjutkan, mohon periksa email Anda dan klik link verifikasi yang telah kami kirimkan.
        </p>

        <p class="text-gray-600 mb-6">
            Jika Anda tidak menerima email, kami dapat mengirimkannya kembali.
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
                    Kirim Ulang Email Verifikasi
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
