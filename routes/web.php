<?php

use App\Http\Controllers\CashflowController;
use App\Http\Controllers\DashboardController;
use App\Livewire\TestAlert;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome')->name('home');
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('cashflow', [CashflowController::class, 'index'])->name('cashflow');
Route::get('import', [CashflowController::class, 'import'])->name('import');

Route::get('/test-alert', function () {
    return app(TestAlert::class);
});
