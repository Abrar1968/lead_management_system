<?php

use App\Models\FollowUpRule;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns html view for browser requests', function () {
    // Create a global rule
    $rule = FollowUpRule::create([
        'user_id' => null,
        'name' => 'Contacted Leads',
        'description' => 'Matches contacted leads',
        'priority' => 10,
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    // Condition: lead.status equals Contacted
    $rule->conditions()->create([
        'field' => 'lead.status',
        'operator' => 'equals',
        'value' => 'Contacted',
    ]);

    // Create matching and non-matching leads
    $matching = Lead::factory()->create(['status' => 'Contacted', 'lead_date' => now()]);
    Lead::factory()->create(['status' => 'New', 'lead_date' => now()]);

    $user = User::factory()->salesPerson()->create();

    $response = $this->actingAs($user)->get(route('follow-up-rules.preview', $rule));

    $response->assertOk();
    $response->assertViewIs('follow-up-rules.preview');

    // View should receive matchingLeads collection
    $matchingLeads = $response->viewData('matchingLeads');
    expect($matchingLeads->count())->toBe(1);
    $response->assertSee($rule->name);
    $response->assertSee($matching->lead_number, false);
});

it('returns json when requested via api', function () {
    $rule = FollowUpRule::create([
        'user_id' => null,
        'name' => 'Contacted Leads API',
        'description' => 'Matches contacted leads',
        'priority' => 10,
        'is_active' => true,
        'logic_type' => 'AND',
    ]);

    $rule->conditions()->create([
        'field' => 'lead.status',
        'operator' => 'equals',
        'value' => 'Contacted',
    ]);

    Lead::factory()->create(['status' => 'Contacted', 'lead_date' => now()]);

    $user = User::factory()->salesPerson()->create();

    $response = $this->actingAs($user)->getJson(route('follow-up-rules.preview', $rule));

    $response->assertOk()->assertJsonStructure(['success', 'total_matches', 'leads']);
    expect($response->json('success'))->toBeTrue();
});
