<?php

use App\Models\FollowUpRule;
use App\Models\FollowUpRuleCondition;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\Service;
use App\Models\User;
use App\Services\AutoFollowUpService;

beforeEach(function () {
    $this->service = new AutoFollowUpService;
    $this->user = User::factory()->create(['role' => 'sales_person']);
    $this->service_model = Service::factory()->create();
});

test('it returns empty collection when no rules exist', function () {
    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class)->toBeEmpty();
});

test('it matches lead with status equals condition', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'New Leads Rule',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'status',
        'operator' => 'equals',
        'value' => 'New',
    ]);

    $matchingLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
    ]);

    $nonMatchingLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['lead']->id)->toBe($matchingLead->id);
});

test('it matches lead with priority condition', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'High Priority Leads',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'High',
    ]);

    $matchingLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'priority' => 'High',
        'status' => 'New',
    ]);

    Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'priority' => 'Low',
        'status' => 'New',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['lead']->id)->toBe($matchingLead->id);
});

test('it applies AND logic correctly', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'High Priority New Leads',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'status',
        'operator' => 'equals',
        'value' => 'New',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'High',
    ]);

    // Matches both conditions
    $matchingLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
        'priority' => 'High',
    ]);

    // Only matches status
    Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
        'priority' => 'Low',
    ]);

    // Only matches priority
    Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
        'priority' => 'High',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['lead']->id)->toBe($matchingLead->id);
});

test('it applies OR logic correctly', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'New or High Priority',
        'is_active' => true,
        'logic_type' => 'OR',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'status',
        'operator' => 'equals',
        'value' => 'New',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'High',
    ]);

    // Matches status only
    $lead1 = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
        'priority' => 'Low',
    ]);

    // Matches priority only
    $lead2 = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
        'priority' => 'High',
    ]);

    // Matches neither
    Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
        'priority' => 'Low',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(2);
    $leadIds = collect($result)->pluck('lead.id')->toArray();
    expect($leadIds)->toContain($lead1->id, $lead2->id);
});

test('it evaluates greater_than operator', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Multiple Calls Made',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'total_calls',
        'operator' => 'greater_than',
        'value' => 2,
    ]);

    $leadWithManyCalls = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
    ]);

    LeadContact::factory()->count(3)->create([
        'lead_id' => $leadWithManyCalls->id,
    ]);

    $leadWithFewCalls = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'Contacted',
    ]);

    LeadContact::factory()->count(1)->create([
        'lead_id' => $leadWithFewCalls->id,
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['lead']->id)->toBe($leadWithManyCalls->id);
});

test('it ignores inactive rules', function () {
    $activeRule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Active Rule',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $activeRule->id,
        'field' => 'status',
        'operator' => 'equals',
        'value' => 'New',
    ]);

    $inactiveRule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Inactive Rule',
        'is_active' => false,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $inactiveRule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'High',
    ]);

    $lead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
        'priority' => 'High',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['rules'])->toHaveCount(1);
    expect($result[0]['rules'][0]['name'])->toBe('Active Rule');
});

test('it includes global rules for all users', function () {
    // Global rule (no user_id)
    $globalRule = FollowUpRule::factory()->create([
        'user_id' => null,
        'name' => 'Global Rule',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $globalRule->id,
        'field' => 'status',
        'operator' => 'equals',
        'value' => 'New',
    ]);

    $lead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'status' => 'New',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(1);
    expect($result[0]['rules'][0]['name'])->toBe('Global Rule');
});

test('preview rule matches returns correct leads', function () {
    $rule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Test Rule',
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $rule->id,
        'field' => 'source',
        'operator' => 'equals',
        'value' => 'WhatsApp',
    ]);

    Lead::factory()->count(5)->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'source' => 'WhatsApp',
        'status' => 'New',
    ]);

    Lead::factory()->count(3)->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'source' => 'Messenger',
        'status' => 'New',
    ]);

    $result = $this->service->previewRuleMatches($rule, 10);

    expect($result)->toHaveCount(5);
    expect($result->every(fn ($lead) => $lead->source === 'WhatsApp'))->toBeTrue();
});

test('it sorts results by rule priority', function () {
    $highPriorityRule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'High Priority Rule',
        'is_active' => true,
        'logic_type' => 'AND',
        'priority' => 0,
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $highPriorityRule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'High',
    ]);

    $lowPriorityRule = FollowUpRule::factory()->create([
        'user_id' => $this->user->id,
        'name' => 'Low Priority Rule',
        'is_active' => true,
        'logic_type' => 'AND',
        'priority' => 10,
    ]);

    FollowUpRuleCondition::create([
        'rule_id' => $lowPriorityRule->id,
        'field' => 'priority',
        'operator' => 'equals',
        'value' => 'Low',
    ]);

    $highPriorityLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'priority' => 'High',
        'status' => 'New',
    ]);

    $lowPriorityLead = Lead::factory()->create([
        'assigned_to' => $this->user->id,
        'service_id' => $this->service_model->id,
        'priority' => 'Low',
        'status' => 'New',
    ]);

    $result = $this->service->getMatchingLeads($this->user);

    expect($result)->toHaveCount(2);
    expect($result[0]['lead']->id)->toBe($highPriorityLead->id);
    expect($result[1]['lead']->id)->toBe($lowPriorityLead->id);
});
