<?php

namespace App\Services;

use App\Models\FollowUpRule;
use App\Models\FollowUpRuleCondition;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AutoFollowUpService
{
    /**
     * Get leads matching active rules for a user.
     *
     * @return \Illuminate\Support\Collection<int, array{lead: Lead, rules: array}>
     */
    public function getMatchingLeads(?User $user = null): \Illuminate\Support\Collection
    {
        // Get active rules for the user
        $rules = $this->getActiveRulesForUser($user);

        if ($rules->isEmpty()) {
            return collect([]);
        }

        // Get leads to evaluate
        $leads = $this->getLeadsToEvaluate($user);

        $matchingLeads = collect([]);

        foreach ($leads as $lead) {
            $matchedRules = [];

            foreach ($rules as $rule) {
                if ($this->leadMatchesRule($lead, $rule)) {
                    $matchedRules[] = [
                        'id' => $rule->id,
                        'name' => $rule->name,
                        'priority' => $rule->priority,
                    ];
                }
            }

            if (! empty($matchedRules)) {
                $matchingLeads->push([
                    'lead' => $lead,
                    'rules' => $matchedRules,
                ]);
            }
        }

        // Sort by highest priority rule matched (lower number = higher priority)
        return $matchingLeads->sort(function ($a, $b) {
            $aPriority = min(array_column($a['rules'], 'priority'));
            $bPriority = min(array_column($b['rules'], 'priority'));

            return $aPriority <=> $bPriority;
        })->values();
    }

    /**
     * Preview leads that match a specific rule.
     *
     * @return Collection<Lead>
     */
    public function previewRuleMatches(FollowUpRule $rule, int $limit = 10): Collection
    {
        $user = $rule->user;
        $leads = $this->getLeadsToEvaluate($user);

        $matchingLeads = $leads->filter(function ($lead) use ($rule) {
            return $this->leadMatchesRule($lead, $rule);
        });

        return $matchingLeads->take($limit);
    }

    /**
     * Check if a lead matches a specific rule.
     */
    public function leadMatchesRule(Lead $lead, FollowUpRule $rule): bool
    {
        $conditions = $rule->conditions;

        if ($conditions->isEmpty()) {
            return false;
        }

        $results = [];

        foreach ($conditions as $condition) {
            $results[] = $this->evaluateCondition($lead, $condition);
        }

        // Apply logic type (AND/OR)
        if ($rule->logic_type === 'AND') {
            return ! in_array(false, $results, true);
        }

        // OR logic
        return in_array(true, $results, true);
    }

    /**
     * Evaluate a single condition against a lead.
     */
    protected function evaluateCondition(Lead $lead, FollowUpRuleCondition $condition): bool
    {
        $fieldValue = $this->getFieldValue($lead, $condition->field);
        $operator = $condition->operator;
        $conditionValue = $condition->value;

        return match ($operator) {
            'equals' => $this->evaluateEquals($fieldValue, $conditionValue),
            'not_equals' => ! $this->evaluateEquals($fieldValue, $conditionValue),
            'greater_than' => $this->evaluateGreaterThan($fieldValue, $conditionValue),
            'less_than' => $this->evaluateLessThan($fieldValue, $conditionValue),
            'greater_or_equal' => $this->evaluateGreaterOrEqual($fieldValue, $conditionValue),
            'less_or_equal' => $this->evaluateLessOrEqual($fieldValue, $conditionValue),
            'between' => $this->evaluateBetween($fieldValue, $conditionValue),
            'in' => $this->evaluateIn($fieldValue, $conditionValue),
            'not_in' => ! $this->evaluateIn($fieldValue, $conditionValue),
            'is_null' => is_null($fieldValue),
            'is_not_null' => ! is_null($fieldValue),
            default => false,
        };
    }

    /**
     * Get the value of a field from the lead (including related data).
     */
    protected function getFieldValue(Lead $lead, string $field): mixed
    {
        return match ($field) {
            // Lead fields
            'status' => $lead->status,
            'priority' => $lead->priority,
            'source' => $lead->source,
            'is_repeat_lead' => $lead->is_repeat_lead,
            'days_since_lead' => $lead->lead_date ? Carbon::parse($lead->lead_date)->diffInDays(now()) : null,

            // Contact fields
            'response_status' => $lead->contacts->last()?->response_status,
            'total_calls' => $lead->contacts->count(),
            'days_since_last_call' => $this->getDaysSinceLastCall($lead),

            // Follow-up fields
            'interest' => $lead->followUps->last()?->interest,
            'pending_count' => $lead->followUps->where('status', 'Pending')->count(),
            'days_since_last' => $this->getDaysSinceLastFollowUp($lead),

            // Meeting fields
            'has_meeting' => $lead->meetings->isNotEmpty(),
            'last_outcome' => $lead->meetings->last()?->outcome,

            default => null,
        };
    }

    /**
     * Get days since last call for a lead.
     */
    protected function getDaysSinceLastCall(Lead $lead): ?int
    {
        $lastContact = $lead->contacts->sortByDesc('call_date')->first();

        if (! $lastContact || ! $lastContact->call_date) {
            return null;
        }

        return Carbon::parse($lastContact->call_date)->diffInDays(now());
    }

    /**
     * Get days since last follow-up for a lead.
     */
    protected function getDaysSinceLastFollowUp(Lead $lead): ?int
    {
        $lastFollowUp = $lead->followUps->sortByDesc('follow_up_date')->first();

        if (! $lastFollowUp || ! $lastFollowUp->follow_up_date) {
            return null;
        }

        return Carbon::parse($lastFollowUp->follow_up_date)->diffInDays(now());
    }

    /**
     * Get active rules for a user.
     */
    protected function getActiveRulesForUser(?User $user): Collection
    {
        $query = FollowUpRule::active()->with('conditions');

        if ($user) {
            if ($user->isAdmin()) {
                // Admin sees all active rules
            } else {
                // Sales person sees global rules + their own
                $query->where(function ($q) use ($user) {
                    $q->whereNull('user_id')
                        ->orWhere('user_id', $user->id);
                });
            }
        } else {
            // No user context - only global rules
            $query->global();
        }

        return $query->orderBy('priority')->get();
    }

    /**
     * Get leads to evaluate for follow-up suggestions.
     * Excludes already converted and lost leads.
     */
    protected function getLeadsToEvaluate(?User $user): Collection
    {
        $query = Lead::with(['contacts', 'followUps', 'meetings'])
            ->whereNotIn('status', ['Converted', 'Lost']);

        if ($user && ! $user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }

        return $query->get();
    }

    /**
     * Comparison helpers.
     */
    protected function evaluateEquals(mixed $fieldValue, mixed $conditionValue): bool
    {
        // Handle boolean comparisons
        if (is_bool($fieldValue)) {
            if (is_string($conditionValue)) {
                $conditionValue = in_array(strtolower($conditionValue), ['true', '1', 'yes']);
            }

            return $fieldValue === (bool) $conditionValue;
        }

        // Handle numeric comparisons
        if (is_numeric($fieldValue) && is_numeric($conditionValue)) {
            return (float) $fieldValue == (float) $conditionValue;
        }

        // String comparison (case-insensitive)
        return strtolower((string) $fieldValue) === strtolower((string) $conditionValue);
    }

    protected function evaluateGreaterThan(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_numeric($fieldValue) || ! is_numeric($conditionValue)) {
            return false;
        }

        return (float) $fieldValue > (float) $conditionValue;
    }

    protected function evaluateLessThan(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_numeric($fieldValue) || ! is_numeric($conditionValue)) {
            return false;
        }

        return (float) $fieldValue < (float) $conditionValue;
    }

    protected function evaluateGreaterOrEqual(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_numeric($fieldValue) || ! is_numeric($conditionValue)) {
            return false;
        }

        return (float) $fieldValue >= (float) $conditionValue;
    }

    protected function evaluateLessOrEqual(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_numeric($fieldValue) || ! is_numeric($conditionValue)) {
            return false;
        }

        return (float) $fieldValue <= (float) $conditionValue;
    }

    protected function evaluateBetween(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_numeric($fieldValue)) {
            return false;
        }

        if (! is_array($conditionValue) || count($conditionValue) !== 2) {
            return false;
        }

        $min = (float) $conditionValue[0];
        $max = (float) $conditionValue[1];
        $value = (float) $fieldValue;

        return $value >= $min && $value <= $max;
    }

    protected function evaluateIn(mixed $fieldValue, mixed $conditionValue): bool
    {
        if (! is_array($conditionValue)) {
            return false;
        }

        // Case-insensitive comparison for strings
        if (is_string($fieldValue)) {
            $conditionValue = array_map('strtolower', $conditionValue);

            return in_array(strtolower($fieldValue), $conditionValue, true);
        }

        return in_array($fieldValue, $conditionValue, true);
    }
}
