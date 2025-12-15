<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommissionController extends Controller
{
    public function __construct(
        private CommissionService $commissionService
    ) {}

    /**
     * Display commission settings page
     */
    public function settings(Request $request): View
    {
        $user = $request->user();
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $monthlyCommission = $this->commissionService->getUserMonthlyCommission($user->id, $month, $year);
        $yearlyCommission = $this->commissionService->getUserYearlyCommission($user->id, $year);
        $breakdown = $this->commissionService->getCommissionBreakdown($user->id, $month, $year);

        return view('commission.settings', [
            'user' => $user,
            'monthlyCommission' => $monthlyCommission,
            'yearlyCommission' => $yearlyCommission,
            'breakdown' => $breakdown,
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * Update commission settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_type' => ['required', 'in:fixed,percentage'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:10000'],
        ], [
            'commission_type.required' => 'Please select a commission type.',
            'commission_rate.required' => 'Please enter a commission rate.',
            'commission_rate.min' => 'Commission rate cannot be negative.',
            'commission_rate.max' => 'Commission rate seems too high. Please check.',
        ]);

        // Additional validation for percentage
        if ($validated['commission_type'] === 'percentage' && $validated['commission_rate'] > 100) {
            return back()->withErrors(['commission_rate' => 'Percentage cannot exceed 100%.']);
        }

        $this->commissionService->updateUserSettings(
            $request->user(),
            $validated['commission_type'],
            $validated['commission_rate']
        );

        return redirect()
            ->route('commission.settings')
            ->with('success', 'Commission settings updated successfully!');
    }
}
