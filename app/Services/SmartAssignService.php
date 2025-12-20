<?php

namespace App\Services;

use App\Models\Conversion;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadAssignmentSetting;
use App\Models\LeadContact;
use App\Models\SalesPerformance;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SmartAssignService
{
    /**
     * Get recommended user for a new lead based on performance and workload.
     */
    public function getRecommendedAssignee(Lead $lead): ?array
    {
        $salesUsers = $this->getActiveSalesUsers();

        if ($salesUsers->isEmpty()) {
            return null;
        }

        $mode = LeadAssignmentSetting::getAssignmentMode();

        return match ($mode) {
            'performance' => $this->getByPerformance($salesUsers),
            'round_robin' => $this->getByRoundRobin($salesUsers),
            default => $this->getByBalanced($salesUsers),
        };
    }

    /**
     * Get all users with their recommendation scores.
     *
     * @return Collection<array{user: User, score: float, metrics: array}>
     */
    public function getAllRecommendations(): Collection
    {
        $salesUsers = $this->getActiveSalesUsers();
        $period = LeadAssignmentSetting::getCalculationPeriod();

        return $salesUsers->map(function ($user) use ($period) {
            $performance = SalesPerformance::getLatestForUser($user->id, $period);

            if (! $performance) {
                // Calculate on the fly if no cached data
                $metrics = $this->calculateUserMetrics($user);
                $score = $this->calculateScore($metrics);
            } else {
                $metrics = [
                    'conversion_rate' => $performance->conversion_rate,
                    'response_rate' => $performance->response_rate,
                    'follow_up_rate' => $performance->follow_up_rate,
                    'avg_deal_value' => $performance->avg_deal_value,
                    'active_leads' => $performance->active_leads,
                    'total_conversions' => $performance->total_conversions,
                    'total_leads' => $performance->total_leads,
                ];
                $score = $performance->performance_score;
            }

            return [
                'user' => $user,
                'score' => round($score, 2),
                'metrics' => $metrics,
                'workload' => $this->getCurrentWorkload($user),
            ];
        })->sortByDesc('score')->values();
    }

    /**
     * Calculate and cache performance for all sales users.
     */
    public function calculateAllPerformance(string $periodType = 'monthly'): void
    {
        $salesUsers = $this->getActiveSalesUsers();
        $period = $this->getPeriodDates($periodType);

        foreach ($salesUsers as $user) {
            $this->calculateAndStorePerformance($user, $periodType, $period);
        }
    }

    /**
     * Calculate and store performance for a single user.
     */
    public function calculateAndStorePerformance(User $user, string $periodType, array $period): SalesPerformance
    {
        $metrics = $this->calculateUserMetrics($user, $period['start'], $period['end']);
        $score = $this->calculateScore($metrics);

        return SalesPerformance::updateOrCreate(
            [
                'user_id' => $user->id,
                'period_type' => $periodType,
                'period_start' => $period['start'],
            ],
            [
                'period_end' => $period['end'],
                'total_leads' => $metrics['total_leads'],
                'total_conversions' => $metrics['total_conversions'],
                'conversion_rate' => $metrics['conversion_rate'],
                'total_revenue' => $metrics['total_revenue'] ?? 0,
                'avg_deal_value' => $metrics['avg_deal_value'],
                'total_calls' => $metrics['total_calls'],
                'total_follow_ups' => $metrics['total_follow_ups'],
                'total_meetings' => $metrics['total_meetings'] ?? 0,
                'response_rate' => $metrics['response_rate'],
                'follow_up_rate' => $metrics['follow_up_rate'],
                'active_leads' => $metrics['active_leads'],
                'pending_follow_ups' => $metrics['pending_follow_ups'] ?? 0,
                'avg_conversion_days' => $metrics['avg_conversion_days'] ?? null,
                'avg_first_contact_hours' => $metrics['avg_first_contact_hours'] ?? null,
                'performance_score' => $score,
            ]
        );
    }

    /**
     * Calculate metrics for a user within a period.
     */
    protected function calculateUserMetrics(User $user, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now();

        // Get leads for this user in period
        $leads = Lead::where('assigned_to', $user->id)
            ->whereBetween('lead_date', [$startDate, $endDate])
            ->get();

        $totalLeads = $leads->count();

        // Conversions
        $conversions = Conversion::where('converted_by', $user->id)
            ->whereBetween('conversion_date', [$startDate, $endDate])
            ->get();

        $totalConversions = $conversions->count();
        $conversionRate = $totalLeads > 0 ? ($totalConversions / $totalLeads) * 100 : 0;

        // Revenue
        $totalRevenue = $conversions->sum('deal_value');
        $avgDealValue = $totalConversions > 0 ? $totalRevenue / $totalConversions : 0;

        // Calls
        $totalCalls = LeadContact::where('caller_id', $user->id)
            ->whereBetween('call_date', [$startDate, $endDate])
            ->count();

        // Follow-ups
        $totalFollowUps = FollowUp::whereHas('lead', function ($q) use ($user) {
            $q->where('assigned_to', $user->id);
        })
            ->whereBetween('follow_up_date', [$startDate, $endDate])
            ->count();

        // Response rate - leads with positive response
        $positiveResponses = ['Yes', 'Interested', '50%', '80%'];
        $leadsWithResponse = Lead::where('assigned_to', $user->id)
            ->whereBetween('lead_date', [$startDate, $endDate])
            ->whereHas('contacts', function ($q) use ($positiveResponses) {
                $q->whereIn('response_status', $positiveResponses);
            })
            ->count();

        $responseRate = $totalLeads > 0 ? ($leadsWithResponse / $totalLeads) * 100 : 0;

        // Follow-up rate
        $leadsWithFollowUp = Lead::where('assigned_to', $user->id)
            ->whereBetween('lead_date', [$startDate, $endDate])
            ->has('followUps')
            ->count();

        $followUpRate = $totalLeads > 0 ? ($leadsWithFollowUp / $totalLeads) * 100 : 0;

        // Active leads (current)
        $activeLeads = Lead::where('assigned_to', $user->id)
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->count();

        return [
            'total_leads' => $totalLeads,
            'total_conversions' => $totalConversions,
            'conversion_rate' => round($conversionRate, 2),
            'total_revenue' => $totalRevenue,
            'avg_deal_value' => round($avgDealValue, 2),
            'total_calls' => $totalCalls,
            'total_follow_ups' => $totalFollowUps,
            'total_meetings' => 0, // Add when meetings tracking is available
            'response_rate' => round($responseRate, 2),
            'follow_up_rate' => round($followUpRate, 2),
            'active_leads' => $activeLeads,
        ];
    }

    /**
     * Calculate performance score based on weighted metrics.
     */
    protected function calculateScore(array $metrics): float
    {
        $weights = LeadAssignmentSetting::getScoringWeights();
        $maxActiveLeads = LeadAssignmentSetting::getMaxActiveLeads();

        // Normalize metrics to 0-100 scale
        $normalizedMetrics = [
            'conversion_rate' => min($metrics['conversion_rate'], 100),
            'response_rate' => min($metrics['response_rate'], 100),
            'follow_up_rate' => min($metrics['follow_up_rate'], 100),
            'avg_deal_value' => $this->normalizeValue($metrics['avg_deal_value'], 0, 100000, 100),
            'workload_balance' => $this->calculateWorkloadScore($metrics['active_leads'], $maxActiveLeads),
        ];

        $score = 0;
        $totalWeight = 0;

        foreach ($weights as $metric => $weight) {
            if (isset($normalizedMetrics[$metric])) {
                $score += $normalizedMetrics[$metric] * ($weight / 100);
                $totalWeight += $weight;
            }
        }

        // Normalize if weights don't sum to 100
        if ($totalWeight > 0 && $totalWeight !== 100) {
            $score = ($score / $totalWeight) * 100;
        }

        return round($score, 2);
    }

    /**
     * Normalize a value to a 0-max scale.
     */
    protected function normalizeValue(float $value, float $min, float $max, float $scale = 100): float
    {
        if ($max <= $min) {
            return 0;
        }

        $normalized = (($value - $min) / ($max - $min)) * $scale;

        return max(0, min($scale, $normalized));
    }

    /**
     * Calculate workload balance score.
     * Higher score = more capacity available.
     */
    protected function calculateWorkloadScore(int $activeLeads, int $maxLeads): float
    {
        if ($maxLeads <= 0) {
            return 0;
        }

        $utilizationRate = min($activeLeads / $maxLeads, 1);

        // Inverse - lower utilization = higher score
        return (1 - $utilizationRate) * 100;
    }

    /**
     * Get current workload for a user.
     */
    protected function getCurrentWorkload(User $user): array
    {
        $activeLeads = Lead::where('assigned_to', $user->id)
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->count();

        $pendingFollowUps = FollowUp::whereHas('lead', function ($q) use ($user) {
            $q->where('assigned_to', $user->id);
        })
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', '<=', now())
            ->count();

        $maxLeads = LeadAssignmentSetting::getMaxActiveLeads();

        return [
            'active_leads' => $activeLeads,
            'pending_follow_ups' => $pendingFollowUps,
            'max_leads' => $maxLeads,
            'capacity_percentage' => round((1 - min($activeLeads / $maxLeads, 1)) * 100, 1),
        ];
    }

    /**
     * Get recommended user by best performance score.
     */
    protected function getByPerformance(Collection $users): ?array
    {
        $recommendations = $this->getAllRecommendations();

        return $recommendations->first();
    }

    /**
     * Get recommended user by balanced score + workload.
     */
    protected function getByBalanced(Collection $users): ?array
    {
        $recommendations = $this->getAllRecommendations()
            ->filter(fn ($r) => $r['workload']['capacity_percentage'] > 0);

        return $recommendations->first();
    }

    /**
     * Get recommended user by round robin (least recent assignment).
     */
    protected function getByRoundRobin(Collection $users): ?array
    {
        $lastAssigned = Lead::selectRaw('assigned_to, MAX(created_at) as last_assigned')
            ->groupBy('assigned_to')
            ->pluck('last_assigned', 'assigned_to');

        $userWithOldestAssignment = $users->sortBy(function ($user) use ($lastAssigned) {
            return $lastAssigned[$user->id] ?? '1970-01-01';
        })->first();

        if (! $userWithOldestAssignment) {
            return null;
        }

        $workload = $this->getCurrentWorkload($userWithOldestAssignment);

        return [
            'user' => $userWithOldestAssignment,
            'score' => 0,
            'metrics' => [],
            'workload' => $workload,
        ];
    }

    /**
     * Get active sales users.
     */
    protected function getActiveSalesUsers(): Collection
    {
        return User::where('role', 'sales_person')
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get period dates based on type.
     */
    protected function getPeriodDates(string $periodType): array
    {
        $now = now();

        return match ($periodType) {
            'daily' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'weekly' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
            ],
            default => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
        };
    }
}
