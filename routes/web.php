<?php

use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\DailyLeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtraCommissionController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MonthlyLeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Daily Leads (Primary View)
    Route::get('/leads/daily', [DailyLeadController::class, 'index'])->name('leads.daily');

    // Monthly Leads
    Route::get('/leads/monthly', [MonthlyLeadController::class, 'index'])->name('leads.monthly');

    // Lead CRUD
    Route::resource('leads', LeadController::class);

    // Repeat Lead Check API
    Route::post('/leads/check-repeat', [LeadController::class, 'checkRepeat'])->name('leads.check-repeat');

    // Commission Settings
    Route::get('/commission/settings', [CommissionController::class, 'settings'])->name('commission.settings');
    Route::put('/commission/settings', [CommissionController::class, 'updateSettings'])->name('commission.update');

    // Conversions
    Route::get('/leads/{lead}/convert', [ConversionController::class, 'create'])->name('conversions.create');
    Route::post('/leads/{lead}/convert', [ConversionController::class, 'store'])->name('conversions.store');

    // Reports (placeholder)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);

    // Extra Commissions Management
    Route::resource('extra-commissions', ExtraCommissionController::class);
    Route::post('/extra-commissions/{extraCommission}/approve', [ExtraCommissionController::class, 'approve'])->name('extra-commissions.approve');
    Route::post('/extra-commissions/{extraCommission}/mark-paid', [ExtraCommissionController::class, 'markPaid'])->name('extra-commissions.mark-paid');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
