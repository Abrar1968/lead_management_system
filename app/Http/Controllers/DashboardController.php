<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadContact;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now();
        $user = auth()->user();

        // Today's Stats
        $todayLeadsQuery = Lead::whereDate('lead_date', $today);
        $todayCallsQuery = LeadContact::whereDate('call_date', $today);
        $todayConversionsQuery = Conversion::whereDate('conversion_date', $today);

        // If sales person, filter by their leads only
        if ($user->isSalesPerson()) {
            $todayLeadsQuery->where('assigned_to', $user->id);
            $todayCallsQuery->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
            $todayConversionsQuery->where('converted_by', $user->id);
        }

        $stats = [
            'today_leads' => $todayLeadsQuery->count(),
            'today_calls' => $todayCallsQuery->count(),
            'pending_follow_ups' => FollowUp::where('status', 'Pending')
                ->whereDate('follow_up_date', '<=', $today)
                ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
                ->count(),
            'today_conversions' => $todayConversionsQuery->count(),
        ];

        // This Month's Stats
        $monthLeadsQuery = Lead::whereMonth('lead_date', $thisMonth->month)
            ->whereYear('lead_date', $thisMonth->year);
        $monthConversionsQuery = Conversion::whereMonth('conversion_date', $thisMonth->month)
            ->whereYear('conversion_date', $thisMonth->year);

        if ($user->isSalesPerson()) {
            $monthLeadsQuery->where('assigned_to', $user->id);
            $monthConversionsQuery->where('converted_by', $user->id);
        }

        $stats['month_leads'] = $monthLeadsQuery->count();
        $stats['month_conversions'] = $monthConversionsQuery->count();
        $stats['month_revenue'] = $monthConversionsQuery->sum('deal_value');
        $stats['month_commission'] = $monthConversionsQuery->sum('commission_amount');

        // Today's Follow-ups
        $todayFollowUps = FollowUp::with(['lead', 'createdBy'])
            ->where('status', 'Pending')
            ->whereDate('follow_up_date', $today)
            ->when($user->isSalesPerson(), fn ($q) => $q->whereHas('lead', fn ($lq) => $lq->where('assigned_to', $user->id)))
            ->orderBy('follow_up_time')
            ->take(10)
            ->get();

        // Recent Leads
        $recentLeads = Lead::with('assignedTo')
            ->when($user->isSalesPerson(), fn ($q) => $q->where('assigned_to', $user->id))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'todayFollowUps', 'recentLeads'));
    }
}
