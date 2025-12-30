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
     * Available report periods
     */
    public const PERIODS = [
        'daily' => 'Daily',
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    /**
     * Get date range based on period type
     */
    private function getDateRange(string $period, ?string $date = null): array
    {
        $baseDate = $date ? Carbon::parse($date) : now();

        return match ($period) {
            'daily' => [
                'start' => $baseDate->copy()->startOfDay(),
                'end' => $baseDate->copy()->endOfDay(),
                'label' => $baseDate->format('F d, Y'),
            ],
            'weekly' => [
                'start' => $baseDate->copy()->startOfWeek(),
                'end' => $baseDate->copy()->endOfWeek(),
                'label' => $baseDate->copy()->startOfWeek()->format('M d').' - '.$baseDate->copy()->endOfWeek()->format('M d, Y'),
            ],
            'yearly' => [
                'start' => $baseDate->copy()->startOfYear(),
                'end' => $baseDate->copy()->endOfYear(),
                'label' => $baseDate->format('Y'),
            ],
            default => [ // monthly
                'start' => $baseDate->copy()->startOfMonth(),
                'end' => $baseDate->copy()->endOfMonth(),
                'label' => $baseDate->format('F Y'),
            ],
        };
    }

    /**
     * Display reports dashboard.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $period = $request->input('period', 'monthly');
        $date = $request->input('date', now()->format('Y-m-d'));

        // Support old 'month' parameter for backwards compatibility
        if ($request->has('month') && ! $request->has('date')) {
            $date = $request->input('month').'-01';
        }

        $dateRange = $this->getDateRange($period, $date);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        $periodLabel = $dateRange['label'];

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
            ->join('services', 'leads.service_id', '=', 'services.id')
            ->selectRaw('services.name as service_name, COUNT(leads.id) as count');
        if (! $isAdmin) {
            $serviceBreakdownQuery->where('leads.assigned_to', $user->id);
        }
        $serviceBreakdown = $serviceBreakdownQuery->groupBy('services.name')->pluck('count', 'service_name');

        // Status breakdown
        $statusBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count');
        if (! $isAdmin) {
            $statusBreakdownQuery->where('assigned_to', $user->id);
        }
        $statusBreakdown = $statusBreakdownQuery->groupBy('status')->pluck('count', 'status');

        // Daily chart data (only for weekly/monthly periods)
        $chartData = [];
        if (in_array($period, ['weekly', 'monthly'])) {
            for ($chartDate = $startDate->copy(); $chartDate <= $endDate; $chartDate->addDay()) {
                $dateStr = $chartDate->format('Y-m-d');
                $leadsOnDate = Lead::whereDate('lead_date', $dateStr);
                if (! $isAdmin) {
                    $leadsOnDate->where('assigned_to', $user->id);
                }
                $chartData[$chartDate->format($period === 'weekly' ? 'D' : 'd')] = $leadsOnDate->count();
            }
        } elseif ($period === 'yearly') {
            // Monthly breakdown for yearly reports
            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::create($startDate->year, $m, 1)->startOfMonth();
                $monthEnd = Carbon::create($startDate->year, $m, 1)->endOfMonth();
                $leadsInMonth = Lead::whereBetween('lead_date', [$monthStart, $monthEnd]);
                if (! $isAdmin) {
                    $leadsInMonth->where('assigned_to', $user->id);
                }
                $chartData[Carbon::create()->month($m)->format('M')] = $leadsInMonth->count();
            }
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

        // Keep month for backwards compatibility
        $month = Carbon::parse($date)->format('Y-m');

        return view('reports.index', compact(
            'month',
            'period',
            'date',
            'periodLabel',
            'totalLeads',
            'totalCalls',
            'totalConversions',
            'totalDealValue',
            'totalCommission',
            'conversionRate',
            'sourceBreakdown',
            'serviceBreakdown',
            'statusBreakdown',
            'chartData',
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
        $period = $request->input('period', 'monthly');
        $date = $request->input('date', now()->format('Y-m-d'));

        // Support old 'month' parameter for backwards compatibility
        if ($request->has('month') && ! $request->has('date')) {
            $date = $request->input('month').'-01';
        }

        $dateRange = $this->getDateRange($period, $date);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];
        $periodLabel = $dateRange['label'];

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
            ->join('services', 'leads.service_id', '=', 'services.id')
            ->selectRaw('services.name as service_name, COUNT(leads.id) as count');
        if (! $isAdmin) {
            $serviceBreakdownQuery->where('leads.assigned_to', $user->id);
        }
        $serviceBreakdown = $serviceBreakdownQuery->groupBy('services.name')->pluck('count', 'service_name');

        // Status breakdown
        $statusBreakdownQuery = Lead::whereBetween('lead_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count');
        if (! $isAdmin) {
            $statusBreakdownQuery->where('assigned_to', $user->id);
        }
        $statusBreakdown = $statusBreakdownQuery->groupBy('status')->pluck('count', 'status');

        // Chart data (only for weekly/monthly periods)
        $chartData = [];
        if (in_array($period, ['weekly', 'monthly'])) {
            for ($chartDate = $startDate->copy(); $chartDate <= $endDate; $chartDate->addDay()) {
                $dateStr = $chartDate->format('Y-m-d');
                $leadsOnDate = Lead::whereDate('lead_date', $dateStr);
                if (! $isAdmin) {
                    $leadsOnDate->where('assigned_to', $user->id);
                }
                $chartData[$chartDate->format($period === 'weekly' ? 'D' : 'd')] = $leadsOnDate->count();
            }
        } elseif ($period === 'yearly') {
            // Monthly breakdown for yearly reports
            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::create($startDate->year, $m, 1)->startOfMonth();
                $monthEnd = Carbon::create($startDate->year, $m, 1)->endOfMonth();
                $leadsInMonth = Lead::whereBetween('lead_date', [$monthStart, $monthEnd]);
                if (! $isAdmin) {
                    $leadsInMonth->where('assigned_to', $user->id);
                }
                $chartData[Carbon::create()->month($m)->format('M')] = $leadsInMonth->count();
            }
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

        // Keep month for backwards compatibility
        $month = Carbon::parse($date)->format('Y-m');

        return view('reports.print', compact(
            'month',
            'period',
            'periodLabel',
            'totalLeads',
            'totalCalls',
            'totalConversions',
            'totalDealValue',
            'totalCommission',
            'conversionRate',
            'sourceBreakdown',
            'serviceBreakdown',
            'statusBreakdown',
            'chartData',
            'conversions',
            'topPerformers',
            'isAdmin'
        ));
    }
}
