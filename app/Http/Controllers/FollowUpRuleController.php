<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowUpRuleRequest;
use App\Models\FollowUpRule;
use App\Models\FollowUpRuleCondition;
use App\Services\AutoFollowUpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FollowUpRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Admin sees all rules, sales person sees only their own + global rules
        if ($user->isAdmin()) {
            $rules = FollowUpRule::with('conditions')
                ->orderBy('priority')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $rules = FollowUpRule::with('conditions')
                ->where(function ($query) use ($user) {
                    $query->whereNull('user_id')
                        ->orWhere('user_id', $user->id);
                })
                ->orderBy('priority')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('follow-up-rules.index', [
            'rules' => $rules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('follow-up-rules.create', [
            'availableFields' => FollowUpRuleCondition::AVAILABLE_FIELDS,
            'operators' => FollowUpRuleCondition::OPERATORS,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpRuleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        DB::transaction(function () use ($data, $user) {
            // Create the rule - user_id is null for admin (global rule)
            $rule = FollowUpRule::create([
                'user_id' => $user->isAdmin() ? null : $user->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'],
                'is_active' => $data['is_active'] ?? true,
                'logic_type' => $data['logic_type'],
            ]);

            // Create conditions
            foreach ($data['conditions'] as $condition) {
                $rule->conditions()->create([
                    'field' => $condition['field'],
                    'operator' => $condition['operator'],
                    'value' => $this->normalizeConditionValue($condition['value'] ?? null),
                ]);
            }
        });

        return redirect()
            ->route('follow-up-rules.index')
            ->with('success', 'Follow-up rule created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FollowUpRule $followUpRule): View
    {
        $this->authorizeRule($followUpRule);

        $followUpRule->load('conditions', 'user');

        return view('follow-up-rules.show', [
            'rule' => $followUpRule,
            'availableFields' => FollowUpRuleCondition::AVAILABLE_FIELDS,
            'operators' => FollowUpRuleCondition::OPERATORS,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FollowUpRule $followUpRule): View
    {
        $this->authorizeRule($followUpRule);

        $followUpRule->load('conditions');

        return view('follow-up-rules.edit', [
            'rule' => $followUpRule,
            'availableFields' => FollowUpRuleCondition::AVAILABLE_FIELDS,
            'operators' => FollowUpRuleCondition::OPERATORS,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFollowUpRuleRequest $request, FollowUpRule $followUpRule): RedirectResponse
    {
        $this->authorizeRule($followUpRule);

        $data = $request->validated();

        DB::transaction(function () use ($data, $followUpRule) {
            // Update the rule
            $followUpRule->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'],
                'is_active' => $data['is_active'] ?? true,
                'logic_type' => $data['logic_type'],
            ]);

            // Delete old conditions and create new ones
            $followUpRule->conditions()->delete();

            foreach ($data['conditions'] as $condition) {
                $followUpRule->conditions()->create([
                    'field' => $condition['field'],
                    'operator' => $condition['operator'],
                    'value' => $this->normalizeConditionValue($condition['value'] ?? null),
                ]);
            }
        });

        return redirect()
            ->route('follow-up-rules.index')
            ->with('success', 'Follow-up rule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FollowUpRule $followUpRule): RedirectResponse
    {
        $this->authorizeRule($followUpRule);

        $followUpRule->delete();

        return redirect()
            ->route('follow-up-rules.index')
            ->with('success', 'Follow-up rule deleted successfully!');
    }

    /**
     * Toggle rule active status.
     */
    public function toggle(FollowUpRule $followUpRule): RedirectResponse
    {
        $this->authorizeRule($followUpRule);

        $followUpRule->update([
            'is_active' => ! $followUpRule->is_active,
        ]);

        $status = $followUpRule->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Follow-up rule {$status} successfully!");
    }

    /**
     * Preview leads matching a rule.
     */
    public function preview(FollowUpRule $followUpRule, AutoFollowUpService $service): JsonResponse
    {
        $this->authorizeRule($followUpRule);

        $followUpRule->load('conditions');

        $matchingLeads = $service->previewRuleMatches($followUpRule, 10);

        return response()->json([
            'success' => true,
            'total_matches' => $matchingLeads->count(),
            'leads' => $matchingLeads->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'lead_number' => $lead->lead_number,
                    'client_name' => $lead->client_name,
                    'phone_number' => $lead->phone_number,
                    'status' => $lead->status,
                    'priority' => $lead->priority,
                    'source' => $lead->source,
                    'lead_date' => $lead->lead_date?->format('Y-m-d'),
                ];
            }),
        ]);
    }

    /**
     * Normalize condition value for storage.
     */
    private function normalizeConditionValue(mixed $value): mixed
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // If it's already an array, return as-is
        if (is_array($value)) {
            return $value;
        }

        // Try to decode JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Return as single value
        return $value;
    }

    /**
     * Authorize that the current user can access this rule.
     */
    private function authorizeRule(FollowUpRule $rule): void
    {
        $user = request()->user();

        // Admin can access all rules
        if ($user->isAdmin()) {
            return;
        }

        // Sales person can only access their own rules or global rules
        if ($rule->user_id !== null && $rule->user_id !== $user->id) {
            abort(403, 'You are not authorized to access this rule.');
        }
    }
}
