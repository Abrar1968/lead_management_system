<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadAssignmentSetting;
use App\Services\SmartAssignService;
use Illuminate\Http\Request;

class SmartAssignController extends Controller
{
    public function __construct(
        protected SmartAssignService $smartAssignService
    ) {}

    /**
     * Display the smart assignment dashboard.
     */
    public function index()
    {
        $recommendations = $this->smartAssignService->getAllRecommendations();

        // Get unassigned leads
        $unassignedLeads = Lead::whereNull('assigned_to')
            ->whereNotIn('status', ['Converted', 'Lost'])
            ->with('service')
            ->orderByDesc('priority')
            ->orderByDesc('lead_date')
            ->limit(10)
            ->get();

        $settings = [
            'assignment_mode' => LeadAssignmentSetting::getAssignmentMode(),
            'max_active_leads' => LeadAssignmentSetting::getMaxActiveLeads(),
            'auto_assign_enabled' => LeadAssignmentSetting::isAutoAssignEnabled(),
            'scoring_weights' => LeadAssignmentSetting::getScoringWeights(),
        ];

        return view('smart-assign.index', compact('recommendations', 'unassignedLeads', 'settings'));
    }

    /**
     * Get recommendation for a specific lead.
     */
    public function recommend(Lead $lead)
    {
        $recommendation = $this->smartAssignService->getRecommendedAssignee($lead);

        return response()->json([
            'success' => true,
            'recommendation' => $recommendation ? [
                'user_id' => $recommendation['user']->id,
                'user_name' => $recommendation['user']->name,
                'score' => $recommendation['score'],
                'workload' => $recommendation['workload'],
            ] : null,
        ]);
    }

    /**
     * Assign lead to recommended user.
     */
    public function assign(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $lead->update([
            'assigned_to' => $validated['user_id'],
        ]);

        return redirect()->back()->with('success', 'Lead assigned successfully!');
    }

    /**
     * Bulk assign leads.
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'leads' => 'required|array',
            'leads.*' => 'exists:leads,id',
            'auto' => 'boolean',
        ]);

        $assigned = 0;

        foreach ($validated['leads'] as $leadId) {
            $lead = Lead::find($leadId);

            if ($lead && is_null($lead->assigned_to)) {
                if ($validated['auto'] ?? true) {
                    $recommendation = $this->smartAssignService->getRecommendedAssignee($lead);
                    if ($recommendation) {
                        $lead->update(['assigned_to' => $recommendation['user']->id]);
                        $assigned++;
                    }
                }
            }
        }

        return redirect()->back()->with('success', "{$assigned} leads assigned successfully!");
    }

    /**
     * Update assignment settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'assignment_mode' => 'required|in:performance,balanced,round_robin',
            'max_active_leads' => 'required|integer|min:1|max:100',
            'auto_assign_enabled' => 'boolean',
            'scoring_weights' => 'array',
            'scoring_weights.conversion_rate' => 'integer|min:0|max:100',
            'scoring_weights.response_rate' => 'integer|min:0|max:100',
            'scoring_weights.follow_up_rate' => 'integer|min:0|max:100',
            'scoring_weights.avg_deal_value' => 'integer|min:0|max:100',
            'scoring_weights.workload_balance' => 'integer|min:0|max:100',
        ]);

        LeadAssignmentSetting::set('assignment_mode', $validated['assignment_mode']);
        LeadAssignmentSetting::set('max_active_leads', $validated['max_active_leads']);
        LeadAssignmentSetting::set('auto_assign_enabled', $validated['auto_assign_enabled'] ?? false);

        if (isset($validated['scoring_weights'])) {
            // Ensure weights sum to 100
            $totalWeight = array_sum($validated['scoring_weights']);
            if ($totalWeight !== 100) {
                return redirect()->back()
                    ->withErrors(['scoring_weights' => 'Scoring weights must sum to 100'])
                    ->withInput();
            }

            LeadAssignmentSetting::set('scoring_weights', $validated['scoring_weights']);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Recalculate performance for all users.
     */
    public function recalculate()
    {
        $period = LeadAssignmentSetting::getCalculationPeriod();
        $this->smartAssignService->calculateAllPerformance($period);

        return redirect()->back()->with('success', 'Performance recalculated successfully!');
    }
}
