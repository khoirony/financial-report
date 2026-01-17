<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\UserController;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
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

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/portal', Portal::class)->name('portal');
    Route::get('/forgot-password', ForgotPassword::class)
        ->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', VerifyEmail::class)
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware('signed')->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'New verification link has been sent!');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/test-alert', function () {
        return app(TestAlert::class);
    });

    Route::middleware('can:is-admin')->group(function () {
        Route::get('dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');
        Route::get('manage-cashflow', [CashflowController::class, 'manageCashflow'])->name('admin.cashflow');
        Route::get('manage-investment', [InvestmentController::class, 'manageInvestment'])->name('admin.investment');
        Route::get('manage-user', [UserController::class, 'manageUser'])->name('admin.user');
    });

    Route::middleware('can:is-user')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('cashflow', [CashflowController::class, 'index'])->name('cashflow');
        Route::get('import', [CashflowController::class, 'import'])->name('import');
        Route::get('investment', [InvestmentController::class, 'index'])->name('investment');
        Route::get('fire-calculator', [InvestmentController::class, 'fireCalculator'])->name('fire');
        Route::get('investment-analysis', [InvestmentController::class, 'analysis'])->name('analysis');
        Route::get('broker-summary', [InvestmentController::class, 'brokerSummary'])->name('broker-summary');
    });
});
