<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\ExtraCommission;
use App\Models\User;
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

    /**
     * Admin: Display all users with commission overview
     */
    public function adminIndex(Request $request): View
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        // Get all active users (salespersons primarily, but include all)
        $users = User::where('is_active', true)
            ->withCount(['leads', 'conversions' => function ($query) use ($month, $year) {
                $query->whereMonth('conversion_date', $month)
                    ->whereYear('conversion_date', $year);
            }])
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        // Calculate commission stats for each user
        $usersWithStats = $users->map(function ($user) use ($month, $year) {
            $monthlyCommission = $this->commissionService->getUserMonthlyCommission($user->id, $month, $year);
            $yearlyCommission = $this->commissionService->getUserYearlyCommission($user->id, $year);

            return [
                'user' => $user,
                'monthly' => $monthlyCommission,
                'yearly' => $yearlyCommission,
                'conversions_this_month' => $user->conversions_count,
            ];
        });

        // Summary stats
        $totalMonthlyCommission = $usersWithStats->sum(fn ($u) => $u['monthly']['total']);
        $totalYearlyCommission = $usersWithStats->sum(fn ($u) => $u['yearly']['total']);
        $totalConversions = Conversion::whereMonth('conversion_date', $month)
            ->whereYear('conversion_date', $year)
            ->count();
        $totalDealValue = Conversion::whereMonth('conversion_date', $month)
            ->whereYear('conversion_date', $year)
            ->sum('deal_value');
        $pendingExtraCommissions = ExtraCommission::where('status', 'Pending')->sum('amount');

        return view('commission.admin-index', [
            'usersWithStats' => $usersWithStats,
            'month' => $month,
            'year' => $year,
            'totalMonthlyCommission' => $totalMonthlyCommission,
            'totalYearlyCommission' => $totalYearlyCommission,
            'totalConversions' => $totalConversions,
            'totalDealValue' => $totalDealValue,
            'pendingExtraCommissions' => $pendingExtraCommissions,
        ]);
    }

    /**
     * Admin: Show edit form for a user's commission settings
     */
    public function adminEdit(User $user): View
    {
        $month = (int) request('month', now()->month);
        $year = (int) request('year', now()->year);

        $monthlyStats = $this->commissionService->getUserMonthlyCommission($user->id, $month, $year);
        $yearlyStats = $this->commissionService->getUserYearlyCommission($user->id, $year);
        $breakdown = $this->commissionService->getCommissionBreakdown($user->id, $month, $year);

        // Get total conversions and deal value for this user this year
        $totalConversions = Conversion::where('converted_by', $user->id)
            ->whereYear('conversion_date', $year)
            ->count();
        $totalDealValue = Conversion::where('converted_by', $user->id)
            ->whereYear('conversion_date', $year)
            ->sum('deal_value');

        // Recent conversions for this user
        $recentConversions = Conversion::with('lead')
            ->where('converted_by', $user->id)
            ->orderByDesc('conversion_date')
            ->take(5)
            ->get();

        return view('commission.admin-edit', [
            'user' => $user,
            'monthlyStats' => $monthlyStats,
            'yearlyStats' => $yearlyStats,
            'totalConversions' => $totalConversions,
            'totalDealValue' => $totalDealValue,
            'recentConversions' => $recentConversions,
            'breakdown' => $breakdown,
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * Admin: Update a user's commission settings
     */
    public function adminUpdate(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'commission_type' => ['required', 'in:fixed,percentage'],
            'default_commission_rate' => ['required', 'numeric', 'min:0', 'max:10000'],
        ], [
            'commission_type.required' => 'Please select a commission type.',
            'default_commission_rate.required' => 'Please enter a commission rate.',
            'default_commission_rate.min' => 'Commission rate cannot be negative.',
            'default_commission_rate.max' => 'Commission rate seems too high. Please check.',
        ]);

        // Additional validation for percentage
        if ($validated['commission_type'] === 'percentage' && $validated['default_commission_rate'] > 100) {
            return back()->withErrors(['default_commission_rate' => 'Percentage cannot exceed 100%.']);
        }

        $this->commissionService->updateUserSettings(
            $user,
            $validated['commission_type'],
            $validated['default_commission_rate']
        );

        return redirect()
            ->route('admin.commissions.index')
            ->with('success', "Commission settings for {$user->name} updated successfully!");
    }
}
