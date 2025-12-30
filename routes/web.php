<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CommissionTypeController;
use App\Http\Controllers\ConversionController;
use App\Http\Controllers\DailyLeadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ExtraCommissionController;
use App\Http\Controllers\FieldDefinitionController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\FollowUpRuleController;
use App\Http\Controllers\LeadContactController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MonthlyLeadController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SmartAssignController;
use App\Http\Controllers\SmartSuggestionsController;
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

    // Notifications
    Route::get('/notifications/check', [NotificationController::class, 'checkUpcomingMeetings'])->name('notifications.check');
    Route::post('/notifications/dismiss', [NotificationController::class, 'dismissLoginAlert'])->name('notifications.dismiss');

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

    // Clients (Converted Leads)
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::post('/clients/{client}/remove-image', [ClientController::class, 'removeImage'])->name('clients.remove-image');
    Route::get('/clients/{client}/preview-document/{fieldId}', [ClientController::class, 'previewDocument'])->name('clients.preview-document');

    // Demos
    Route::resource('demos', DemoController::class);
    Route::post('/demos/{demo}/remove-image', [DemoController::class, 'removeImage'])->name('demos.remove-image');
    Route::get('/demos/{demo}/preview-document/{fieldId}', [DemoController::class, 'previewDocument'])->name('demos.preview-document');

    // Follow-up Rules (Smart Suggestions - Auto Follow-up)
    Route::resource('follow-up-rules', FollowUpRuleController::class);
    Route::post('/follow-up-rules/{follow_up_rule}/toggle', [FollowUpRuleController::class, 'toggle'])->name('follow-up-rules.toggle');
    Route::get('/follow-up-rules/{follow_up_rule}/preview', [FollowUpRuleController::class, 'preview'])->name('follow-up-rules.preview');

    // Smart Assignment
    Route::get('/smart-assign', [SmartAssignController::class, 'index'])->name('smart-assign.index');
    Route::get('/smart-assign/recommend/{lead}', [SmartAssignController::class, 'recommend'])->name('smart-assign.recommend');
    Route::post('/smart-assign/assign/{lead}', [SmartAssignController::class, 'assign'])->name('smart-assign.assign');
    Route::post('/smart-assign/bulk-assign', [SmartAssignController::class, 'bulkAssign'])->name('smart-assign.bulk-assign');
    Route::post('/smart-assign/settings', [SmartAssignController::class, 'updateSettings'])->name('smart-assign.settings');
    Route::post('/smart-assign/recalculate', [SmartAssignController::class, 'recalculate'])->name('smart-assign.recalculate');

    // Smart Suggestions Dashboard (Unified View)
    Route::get('/smart-suggestions', [SmartSuggestionsController::class, 'index'])->name('smart-suggestions.index');
    Route::post('/smart-suggestions/process-followups', [SmartSuggestionsController::class, 'processFollowups'])->name('smart-suggestions.process-followups');

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

    // Field Definitions Management (Dynamic Fields)
    Route::resource('field-definitions', FieldDefinitionController::class)->except(['show']);
    Route::post('/field-definitions/reorder', [FieldDefinitionController::class, 'reorder'])->name('field-definitions.reorder');

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

    // Admin Commission Management
    Route::get('/admin/commissions', [CommissionController::class, 'adminIndex'])->name('admin.commissions.index');
    Route::get('/admin/commissions/{user}/edit', [CommissionController::class, 'adminEdit'])->name('admin.commissions.edit');
    Route::put('/admin/commissions/{user}', [CommissionController::class, 'adminUpdate'])->name('admin.commissions.update');

    // Commission Types Management
    Route::resource('commission-types', CommissionTypeController::class)->names([
        'index' => 'admin.commission-types.index',
        'create' => 'admin.commission-types.create',
        'store' => 'admin.commission-types.store',
        'edit' => 'admin.commission-types.edit',
        'update' => 'admin.commission-types.update',
        'destroy' => 'admin.commission-types.destroy',
    ])->except(['show']);
    Route::get('/commission-types/{commission_type}/users', [CommissionTypeController::class, 'users'])->name('admin.commission-types.users');
    Route::post('/commission-types/{commission_type}/assign', [CommissionTypeController::class, 'assignToUser'])->name('admin.commission-types.assign');
    Route::delete('/commission-types/{commission_type}/remove/{user}', [CommissionTypeController::class, 'removeFromUser'])->name('admin.commission-types.remove');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
