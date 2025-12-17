<?php

use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\DailyLeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtraCommissionController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\LeadContactController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MonthlyLeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
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

    // Follow-ups
    Route::resource('follow-ups', FollowUpController::class)->except(['create', 'show', 'edit']);
    Route::post('/follow-ups/{followUp}/complete', [FollowUpController::class, 'complete'])->name('follow-ups.complete');
    Route::get('/leads/{lead}/follow-ups', [FollowUpController::class, 'forLead'])->name('follow-ups.for-lead');
    Route::post('/leads/{lead}/follow-ups/quick', [FollowUpController::class, 'quickAdd'])->name('follow-ups.quick-add');

    // Lead Contacts (Calls)
    Route::resource('contacts', LeadContactController::class)->except(['create', 'show', 'edit']);
    Route::get('/leads/{lead}/contacts', [LeadContactController::class, 'forLead'])->name('contacts.for-lead');
    Route::post('/leads/{lead}/contacts/quick', [LeadContactController::class, 'quickLog'])->name('contacts.quick-log');

    // Meetings
    Route::resource('meetings', MeetingController::class)->except(['create', 'show', 'edit']);
    Route::post('/meetings/{meeting}/outcome', [MeetingController::class, 'updateOutcome'])->name('meetings.update-outcome');
    Route::get('/leads/{lead}/meetings', [MeetingController::class, 'forLead'])->name('meetings.for-lead');
    Route::post('/leads/{lead}/meetings/quick', [MeetingController::class, 'quickSchedule'])->name('meetings.quick-schedule');

    // Commission Settings
    Route::get('/commission/settings', [CommissionController::class, 'settings'])->name('commission.settings');
    Route::put('/commission/settings', [CommissionController::class, 'updateSettings'])->name('commission.update');

    // Conversions
    Route::get('/leads/{lead}/convert', [ConversionController::class, 'create'])->name('conversions.create');
    Route::post('/leads/{lead}/convert', [ConversionController::class, 'store'])->name('conversions.store');

    // Reports
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);

    // User Delete Confirmation Page
    Route::get('/users/{user}/delete', [UserController::class, 'delete'])->name('users.delete');

    // Bulk Lead Reassignment from User
    Route::post('/users/bulk-reassign-leads', [UserController::class, 'bulkReassignLeads'])->name('users.bulk-reassign-leads');

    // Bulk Lead Operations
    Route::post('/leads/bulk-delete', [LeadController::class, 'bulkDelete'])->name('leads.bulk-delete');
    Route::post('/leads/bulk-reassign', [LeadController::class, 'bulkReassign'])->name('leads.bulk-reassign');
    Route::post('/leads/bulk-status', [LeadController::class, 'bulkUpdateStatus'])->name('leads.bulk-status');

    // Services Management
    Route::resource('services', ServiceController::class);

    // Extra Commissions Management
    Route::resource('extra-commissions', ExtraCommissionController::class)->names([
        'index' => 'admin.extra-commissions.index',
        'create' => 'admin.extra-commissions.create',
        'store' => 'admin.extra-commissions.store',
        'show' => 'admin.extra-commissions.show',
        'edit' => 'admin.extra-commissions.edit',
        'update' => 'admin.extra-commissions.update',
        'destroy' => 'admin.extra-commissions.destroy',
    ]);
    Route::post('/extra-commissions/{extraCommission}/approve', [ExtraCommissionController::class, 'approve'])->name('admin.extra-commissions.approve');
    Route::post('/extra-commissions/{extraCommission}/mark-paid', [ExtraCommissionController::class, 'markPaid'])->name('admin.extra-commissions.mark-paid');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
