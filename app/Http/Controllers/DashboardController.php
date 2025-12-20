<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $today = Carbon::parse($date);
        $thisMonth = $today->copy();
        $user = auth()->user();

        // Today's Stats
        $todayLeadsQuery = Lead::whereDate('lead_date', $today);
        $todayCallsQuery = LeadContact::whereDate('call_date', $today)->whereHas('lead');
        $todayConversionsQuery = Conversion::whereDate('conversion_date', $today)->whereHas('lead');
        $todayMeetingsQuery = Meeting::whereDate('meeting_date', $today)->whereHas('lead');

        // If sales person, filter by their leads only
        if ($user->isSalesPerson()) {
            $todayLeadsQuery->where('assigned_to', $user->id);
            $todayCallsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
            $todayConversionsQuery->where('converted_by', $user->id);
            $todayMeetingsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        $stats = [
            'today_leads' => $todayLeadsQuery->count(),
            'today_calls' => $todayCallsQuery->count(),
            'today_meetings' => $todayMeetingsQuery->count(),
            'pending_follow_ups' => FollowUp::where('status', 'Pending')
                ->whereDate('follow_up_date', '<=', $today)
                ->whereHas('lead')
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
            'today_conversions' => $todayConversionsQuery->count(),
            'unassigned_leads' => Lead::whereNull('assigned_to')
                ->whereNotIn('status', ['Converted', 'Lost'])
                ->count(),
        ];

        // This Month's Stats
        $monthLeadsQuery = Lead::whereMonth('lead_date', $thisMonth->month)
            ->whereYear('lead_date', $thisMonth->year);
        $monthConversionsQuery = Conversion::whereMonth('conversion_date', $thisMonth->month)
            ->whereYear('conversion_date', $thisMonth->year)
            ->whereHas('lead');
        $monthCallsQuery = LeadContact::whereMonth('call_date', $thisMonth->month)
            ->whereYear('call_date', $thisMonth->year)
            ->whereHas('lead');

        if ($user->isSalesPerson()) {
            $monthLeadsQuery->where('assigned_to', $user->id);
            $monthConversionsQuery->where('converted_by', $user->id);
            $monthCallsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        $stats['month_leads'] = $monthLeadsQuery->count();
        $stats['month_conversions'] = $monthConversionsQuery->count();
        $stats['month_revenue'] = $monthConversionsQuery->sum('deal_value');
        $stats['month_commission'] = $monthConversionsQuery->sum('commission_amount');
        $stats['month_calls'] = $monthCallsQuery->count();

        // Advanced Analytics
        $analytics = $this->calculateAdvancedAnalytics($user, $thisMonth);

        // Today's Follow-ups
        $todayFollowUps = FollowUp::with(['lead', 'createdBy'])
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', $today)
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_time')
            ->take(10)
            ->get();

        // Overdue Follow-ups
        $overdueFollowUps = FollowUp::with(['lead'])
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', '<', $today)
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_date')
            ->take(5)
            ->get();

        // Today's Meetings
        $todayMeetings = Meeting::with(['lead'])
            ->whereDate('meeting_date', $today)
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('meeting_time')
            ->get();

        // Recent Leads
        $recentLeads = Lead::with('assignedTo')
            ->when($user->isSalesPerson(), fn ($q) => $q->where('assigned_to', $user->id))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Top Performing Users (This Month)
        $topPerformers = [];
        if ($user->isAdmin()) {
            $topPerformers = User::where('role', 'sales')
                ->withCount(['conversions' => function($q) use ($thisMonth) {
                    $q->whereMonth('conversion_date', $thisMonth->month)
                      ->whereYear('conversion_date', $thisMonth->year);
                }])
                ->withSum(['conversions' => function($q) use ($thisMonth) {
                    $q->whereMonth('conversion_date', $thisMonth->month)
                      ->whereYear('conversion_date', $thisMonth->year);
                }], 'deal_value')
                ->orderBy('conversions_count', 'desc')
                ->take(5)
                ->get();
        }

        // Call Response Breakdown (Today)
        $responseBreakdown = LeadContact::whereDate('call_date', $today)
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->selectRaw('response_status, COUNT(*) as count')
            ->groupBy('response_status')
            ->pluck('count', 'response_status')
            ->toArray();

        return view('dashboard', compact(
            'stats',
            'analytics',
            'todayFollowUps',
            'overdueFollowUps',
            'todayMeetings',
            'recentLeads',
            'responseBreakdown',
            'topPerformers',
            'date'
        ));
    }

    /**
     * Calculate advanced analytics for the dashboard.
     */
    private function calculateAdvancedAnalytics($user, $month): array
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        // Base queries with user filter
        $leadsQuery = Lead::whereBetween('lead_date', [$startOfMonth, $endOfMonth]);
        $conversionsQuery = Conversion::whereBetween('conversion_date', [$startOfMonth, $endOfMonth])->whereHas('lead');
        $callsQuery = LeadContact::whereBetween('call_date', [$startOfMonth, $endOfMonth])->whereHas('lead');
        $followUpsQuery = FollowUp::whereBetween('follow_up_date', [$startOfMonth, $endOfMonth])->whereHas('lead');

        if ($user->isSalesPerson()) {
            $leadsQuery->where('assigned_to', $user->id);
            $conversionsQuery->where('converted_by', $user->id);
            $callsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
            $followUpsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        // Get counts
        $totalLeads = $leadsQuery->count();
        $totalConversions = $conversionsQuery->count();
        $totalCalls = $callsQuery->count();
        $completedFollowUps = $followUpsQuery->clone()->where('status', 'Completed')->count();
        $totalFollowUps = $followUpsQuery->count();

        // Get response counts
        $positiveResponses = LeadContact::whereBetween('call_date', [$startOfMonth, $endOfMonth])
            ->whereHas('lead')
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->whereIn('response_status', ['Yes', 'Interested', '50%'])
            ->count();

        // Lead status breakdown
        $statusBreakdown = Lead::whereBetween('lead_date', [$startOfMonth, $endOfMonth])
            ->when($user->isSalesPerson(), fn ($q) => $q->where('assigned_to', $user->id))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Source breakdown
        $sourceBreakdown = Lead::whereBetween('lead_date', [$startOfMonth, $endOfMonth])
            ->when($user->isSalesPerson(), fn ($q) => $q->where('assigned_to', $user->id))
            ->selectRaw('source, COUNT(*) as count')
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        // Calculate ratios
        $conversionRate = $totalLeads > 0 ? round(($totalConversions / $totalLeads) * 100, 2) : 0;
        $responseRate = $totalCalls > 0 ? round(($positiveResponses / $totalCalls) * 100, 2) : 0;
        $followUpCompletionRate = $totalFollowUps > 0 ? round(($completedFollowUps / $totalFollowUps) * 100, 2) : 0;
        $callsPerLead = $totalLeads > 0 ? round($totalCalls / $totalLeads, 2) : 0;

        // Average deal value
        $avgDealValue = $conversionsQuery->clone()->avg('deal_value') ?? 0;

        return [
            'conversion_rate' => $conversionRate,
            'response_rate' => $responseRate,
            'follow_up_completion_rate' => $followUpCompletionRate,
            'calls_per_lead' => $callsPerLead,
            'avg_deal_value' => round($avgDealValue, 2),
            'status_breakdown' => $statusBreakdown,
            'source_breakdown' => $sourceBreakdown,
            'total_leads' => $totalLeads,
            'total_conversions' => $totalConversions,
            'total_calls' => $totalCalls,
            'positive_responses' => $positiveResponses,
        ];
    }
}
