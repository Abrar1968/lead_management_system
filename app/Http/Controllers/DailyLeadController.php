<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyLeadController extends Controller
{
    public function __construct(
        private LeadService $leadService
    ) {}

    /**
     * Display the daily leads view
     */
    public function index(Request $request): View
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $user = $request->user();

        // Get filters from request
        $source = $request->input('source');
        $service = $request->input('service');
        $status = $request->input('status');
        $priority = $request->input('priority');

        // Get leads with filters
        $leads = $this->leadService->getLeadsByDateWithFilters(
            $date,
            $user,
            $source,
            $service,
            $status,
            $priority
        );

        // Get daily summary stats
        $summary = $this->leadService->getDailySummary($date, $user);

        // Get date navigation data
        $dateNav = $this->leadService->getDateNavigation($date);

        // Get active services for filter dropdown
        $services = \App\Models\Service::active()->get();

        return view('leads.daily', [
            'leads' => $leads,
            'summary' => $summary,
            'dateNav' => $dateNav,
            'services' => $services,
            'filters' => [
                'source' => $source,
                'service' => $service,
                'status' => $status,
                'priority' => $priority,
            ],
        ]);
    }
}
