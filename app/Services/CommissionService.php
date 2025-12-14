<?php

namespace App\Services;

use App\Models\Conversion;
use App\Models\ExtraCommission;
use App\Models\User;
use Illuminate\Support\Carbon;

class CommissionService
{
    /**
     * Calculate commission based on user settings and deal value
     */
    public function calculateCommission(User $user, float $dealValue): float
    {
        if ($user->commission_type === 'fixed') {
            return round($user->default_commission_rate, 2);
        }

        // Percentage calculation
        return round(($dealValue * $user->default_commission_rate) / 100, 2);
    }

    /**
     * Get user's total commission for a specific month
     */
    public function getUserMonthlyCommission(int $userId, int $month, int $year): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $standardCommission = (float) Conversion::where('converted_by', $userId)
            ->whereBetween('conversion_date', [$startDate, $endDate])
            ->sum('commission_amount');

        $extraCommission = (float) ExtraCommission::where('user_id', $userId)
            ->whereBetween('date_earned', [$startDate, $endDate])
            ->whereIn('status', ['Approved', 'Paid'])
            ->sum('amount');

        return [
            'standard' => $standardCommission,
            'extra' => $extraCommission,
            'total' => $standardCommission + $extraCommission,
        ];
    }

    /**
     * Get user's year-to-date commission
     */
    public function getUserYearlyCommission(int $userId, int $year): array
    {
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        $standardCommission = (float) Conversion::where('converted_by', $userId)
            ->whereBetween('conversion_date', [$startDate, $endDate])
            ->sum('commission_amount');

        $extraCommission = (float) ExtraCommission::where('user_id', $userId)
            ->whereBetween('date_earned', [$startDate, $endDate])
            ->whereIn('status', ['Approved', 'Paid'])
            ->sum('amount');

        return [
            'standard' => $standardCommission,
            'extra' => $extraCommission,
            'total' => $standardCommission + $extraCommission,
        ];
    }

    /**
     * Get commission breakdown for a user in a specific month
     *
     * @param  User|int  $userOrId  User model or user ID
     * @param  string|int  $monthOrMonth  Month in Y-m format or month number
     * @param  int|null  $year  Year (only needed if $monthOrMonth is int)
     */
    public function getCommissionBreakdown(User|int $userOrId, string|int|null $monthOrMonth = null, ?int $year = null): array
    {
        // Handle User or int
        $userId = $userOrId instanceof User ? $userOrId->id : $userOrId;

        // Handle Y-m string format or separate month/year
        if (is_string($monthOrMonth) && str_contains($monthOrMonth, '-')) {
            $parsedDate = Carbon::parse($monthOrMonth.'-01');
            $startDate = $parsedDate->startOfMonth();
            $endDate = $parsedDate->copy()->endOfMonth();
        } else {
            $startDate = Carbon::create($year, (int) $monthOrMonth, 1)->startOfMonth();
            $endDate = Carbon::create($year, (int) $monthOrMonth, 1)->endOfMonth();
        }

        $conversions = Conversion::with('lead')
            ->where('converted_by', $userId)
            ->whereBetween('conversion_date', [$startDate, $endDate])
            ->orderBy('conversion_date', 'desc')
            ->get();

        $extraCommissions = ExtraCommission::where('user_id', $userId)
            ->whereBetween('date_earned', [$startDate, $endDate])
            ->orderBy('date_earned', 'desc')
            ->get();

        return [
            'conversions' => $conversions,
            'extra_commissions' => $extraCommissions,
            'conversion_commission' => (float) $conversions->sum('commission_amount'),
            'extra_commission' => (float) $extraCommissions->whereIn('status', ['Approved', 'Paid'])->sum('amount'),
            'pending_extra' => (float) $extraCommissions->where('status', 'Pending')->sum('amount'),
            'total' => (float) ($conversions->sum('commission_amount') + $extraCommissions->whereIn('status', ['Approved', 'Paid'])->sum('amount')),
        ];
    }

    /**
     * Update user commission settings
     */
    public function updateUserSettings(User $user, string $commissionType, float $rate): User
    {
        $user->update([
            'commission_type' => $commissionType,
            'default_commission_rate' => $rate,
        ]);

        return $user->fresh();
    }

    /**
     * Get all users with their commission stats
     */
    public function getAllUsersWithStats(int $month, int $year): array
    {
        $users = User::where('is_active', true)->get();

        return $users->map(function ($user) use ($month, $year) {
            $monthlyStats = $this->getUserMonthlyCommission($user->id, $month, $year);

            return [
                'user' => $user,
                'monthly_commission' => $monthlyStats['total'],
                'conversions_count' => Conversion::where('converted_by', $user->id)
                    ->whereMonth('conversion_date', $month)
                    ->whereYear('conversion_date', $year)
                    ->count(),
            ];
        })->toArray();
    }
}
