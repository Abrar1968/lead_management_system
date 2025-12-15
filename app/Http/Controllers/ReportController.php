<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // For admin: get all data, for sales_person: only their data
        $isAdmin = $user->role === 'admin';

        // Lead stats
        $leadsQuery = Lead::whereBetween('lead_date', [$startDate, $endDate]);
        if (! $isAdmin) {
            $leadsQuery->where('assigned_to', $user->id);
        }
        $totalLeads = $leadsQuery->count();

        // Calls made
        $callsQuery = LeadContact::whereBetween('call_date', [$startDate, $endDate])
            ->whereHas('lead');
        if (! $isAdmin) {
            $callsQuery->where('contacted_by', $user->id);
        }
        $totalCalls = $callsQuery->count();

        // Conversions
        $conversionsQuery = Conversion::whereBetween('conversion_date', [$startDate, $endDate])
            ->whereHas('lead');
        if (! $isAdmin) {
            $conversionsQuery->where('converted_by', $user->id);
        }
        $conversions = $conversionsQuery->with('lead')->get();
        $totalConversions = $conversions->count();
        $totalDealValue = $conversions->sum('deal_value');
        $totalCommission = $conversions->sum('commission_amount');

        // Conversion rate
        $conversionRate = $totalLeads > 0 ? round(($totalConversions / $totalLeads) * 100, 1) : 0;

        // Source breakdown
        $sourceBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('source, COUNT(*) as count');
        if (! $isAdmin) {
            $sourceBreakdownQuery->where('assigned_to', $user->id);
        }
        $sourceBreakdown = $sourceBreakdownQuery->groupBy('source')->pluck('count', 'source');

        // Service breakdown
        $serviceBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('service_interested, COUNT(*) as count');
        if (! $isAdmin) {
            $serviceBreakdownQuery->where('assigned_to', $user->id);
        }
        $serviceBreakdown = $serviceBreakdownQuery->groupBy('service_interested')->pluck('count', 'service_interested');

        // Status breakdown
        $statusBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count');
        if (! $isAdmin) {
            $statusBreakdownQuery->where('assigned_to', $user->id);
        }
        $statusBreakdown = $statusBreakdownQuery->groupBy('status')->pluck('count', 'status');

        // Daily chart data
        $dailyData = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $leadsOnDate = Lead::whereDate('lead_date', $dateStr);
            if (! $isAdmin) {
                $leadsOnDate->where('assigned_to', $user->id);
            }
            $dailyData[$date->format('d')] = $leadsOnDate->count();
        }

        // Top performers (admin only)
        $topPerformers = collect();
        if ($isAdmin) {
            $topPerformers = User::where('role', 'sales_person')
                ->withCount(['conversions' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('conversion_date', [$startDate, $endDate]);
                }])
                ->orderByDesc('conversions_count')
                ->limit(5)
                ->get()
                ->filter(fn ($user) => $user->conversions_count > 0);
        }

        return view('reports.index', compact(
            'month',
            'totalLeads',
            'totalCalls',
            'totalConversions',
            'totalDealValue',
            'totalCommission',
            'conversionRate',
            'sourceBreakdown',
            'serviceBreakdown',
            'statusBreakdown',
            'dailyData',
            'conversions',
            'topPerformers',
            'isAdmin'
        ));
    }

    /**
     * Display printable report.
     */
    public function print(Request $request): View
    {
        $user = auth()->user();
        $month = $request->input('month', now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        // For admin: get all data, for sales_person: only their data
        $isAdmin = $user->role === 'admin';

        // Lead stats
        $leadsQuery = Lead::whereBetween('lead_date', [$startDate, $endDate]);
        if (! $isAdmin) {
            $leadsQuery->where('assigned_to', $user->id);
        }
        $totalLeads = $leadsQuery->count();

        // Calls made
        $callsQuery = LeadContact::whereBetween('call_date', [$startDate, $endDate])
            ->whereHas('lead');
        if (! $isAdmin) {
            $callsQuery->where('contacted_by', $user->id);
        }
        $totalCalls = $callsQuery->count();

        // Conversions
        $conversionsQuery = Conversion::whereBetween('conversion_date', [$startDate, $endDate])
            ->whereHas('lead');
        if (! $isAdmin) {
            $conversionsQuery->where('converted_by', $user->id);
        }
        $conversions = $conversionsQuery->with('lead')->get();
        $totalConversions = $conversions->count();
        $totalDealValue = $conversions->sum('deal_value');
        $totalCommission = $conversions->sum('commission_amount');

        // Conversion rate
        $conversionRate = $totalLeads > 0 ? round(($totalConversions / $totalLeads) * 100, 1) : 0;

        // Source breakdown
        $sourceBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('source, COUNT(*) as count');
        if (! $isAdmin) {
            $sourceBreakdownQuery->where('assigned_to', $user->id);
        }
        $sourceBreakdown = $sourceBreakdownQuery->groupBy('source')->pluck('count', 'source');

        // Service breakdown
        $serviceBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('service_interested, COUNT(*) as count');
        if (! $isAdmin) {
            $serviceBreakdownQuery->where('assigned_to', $user->id);
        }
        $serviceBreakdown = $serviceBreakdownQuery->groupBy('service_interested')->pluck('count', 'service_interested');

        // Status breakdown
        $statusBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count');
        if (! $isAdmin) {
            $statusBreakdownQuery->where('assigned_to', $user->id);
        }
        $statusBreakdown = $statusBreakdownQuery->groupBy('status')->pluck('count', 'status');

        // Daily chart data
        $dailyData = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $leadsOnDate = Lead::whereDate('lead_date', $dateStr);
            if (! $isAdmin) {
                $leadsOnDate->where('assigned_to', $user->id);
            }
            $dailyData[$date->format('d')] = $leadsOnDate->count();
        }

        // Top performers (admin only)
        $topPerformers = collect();
        if ($isAdmin) {
            $topPerformers = User::where('role', 'sales_person')
                ->withCount(['conversions' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('conversion_date', [$startDate, $endDate]);
                }])
                ->orderByDesc('conversions_count')
                ->limit(5)
                ->get()
                ->filter(fn ($user) => $user->conversions_count > 0);
        }

        return view('reports.print', compact(
            'month',
            'totalLeads',
            'totalCalls',
            'totalConversions',
            'totalDealValue',
            'totalCommission',
            'conversionRate',
            'sourceBreakdown',
            'serviceBreakdown',
            'statusBreakdown',
            'dailyData',
            'conversions',
            'topPerformers',
            'isAdmin'
        ));
    }
}
