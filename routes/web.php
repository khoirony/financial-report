<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Livewire\TestAlert;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
});

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('cashflow', [CashflowController::class, 'index'])->name('cashflow');
    Route::get('import', [CashflowController::class, 'import'])->name('import');

    Route::get('/test-alert', function () {
        return app(TestAlert::class);
    });

    Route::get('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama setelah logout
    })->name('logout');
});
