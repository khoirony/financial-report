<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Livewire\Auth\VerifyEmail; // Pastikan 'use' ini ada
use App\Livewire\Portal;
use App\Livewire\TestAlert;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// 1. Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/portal', Portal::class)->name('portal');
});

Route::middleware('auth')->group(function () {
    // 1. Rute untuk menampilkan halaman "mohon verifikasi email"
    Route::get('/email/verify', VerifyEmail::class)
        ->name('verification.notice');
    
    // 2. Rute yang menangani link dari email (saat user mengklik link)
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware('signed')->name('verification.verify'); // 'auth' sudah ada dari grup
    
    // 3. Rute untuk mengirim ulang email verifikasi
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim!');
    })->middleware('throttle:6,1')->name('verification.send'); // 'auth' sudah ada dari grup

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); // Redirect ke halaman login setelah logout
    })->name('logout');
});


// 3. Route yang memerlukan autentikasi DAN verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('cashflow', [CashflowController::class, 'index'])->name('cashflow');
    Route::get('import', [CashflowController::class, 'import'])->name('import');
    Route::get('investment', [InvestmentController::class, 'index'])->name('investment');

    Route::get('/test-alert', function () {
        return app(TestAlert::class);
    });
});
