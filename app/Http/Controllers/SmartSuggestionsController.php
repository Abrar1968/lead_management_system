<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\AutoFollowUpService;
use App\Services\SmartAssignService;

class SmartSuggestionsController extends Controller
{
    public function __construct(
        protected AutoFollowUpService $followUpService,
        protected SmartAssignService $assignService
    ) {}

    /**
     * Display the unified smart suggestions dashboard.
     */
    public function index()
    {
        // Get follow-up suggestions
        $followUpSuggestions = $this->followUpService->getMatchingLeads();

        // Get assignment recommendations
        $assignmentRecommendations = $this->assignService->getAllRecommendations();

        // Get unassigned leads
        $unassignedLeads = Lead::whereNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->with(['service'])
            ->orderByDesc('priority')
            ->orderByDesc('lead_date')
            ->limit(10)
            ->get();

        // Get summary stats
        $stats = [
            'pending_follow_ups' => $followUpSuggestions->count(),
            'unassigned_leads' => Lead::whereNull('assigned_to')
                ->whereNotIn('status', ['Converted', 'Lost'])
                ->count(),
            'active_sales_persons' => $assignmentRecommendations->count(),
            'avg_capacity' => $assignmentRecommendations->avg(fn ($r) => $r['workload']['capacity_percentage']) ?? 0,
        ];

        return view('smart-suggestions.index', compact(
            'followUpSuggestions',
            'assignmentRecommendations',
            'unassignedLeads',
            'stats'
        ));
    }

    /**
     * Manually process auto follow-up rules and create follow-ups.
     */
    public function processFollowups()
    {
        $result = $this->followUpService->processAutoFollowups(auth()->user()->isAdmin() ? null : auth()->user());

        $message = sprintf(
            'Auto follow-up processing complete! Created %d new follow-ups, skipped %d existing.',
            $result['created'],
            $result['skipped']
        );

        return redirect()->route('dashboard')->with('success', $message);
    }
}
