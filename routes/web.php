<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'getStatistics'])->name('statistics');

    Route::prefix('/income')->group(function () {
        Route::post('/', [IncomeController::class, 'store'])->name('income.store');
    });

    Route::prefix('/expense')->group(function () {
        Route::post('/', [ExpenseController::class, 'store'])->name('expense.store');
        Route::put('/{expense_payment}/paid', [ExpenseController::class, 'markPaid'])->name('expense.mark-paid');
    });
});
