<?php

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\SalesPerformance;
use App\Models\User;
use App\Services\SmartAssignService;

beforeEach(function () {
    $this->service = new SmartAssignService;
});

it('returns null when no sales users exist', function () {
    // Create an admin user to assign the lead to (not a sales_person)
    $adminUser = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $lead = Lead::factory()->create([
        'assigned_to' => $adminUser->id,
    ]);

    $result = $this->service->getRecommendedAssignee($lead);

    expect($result)->toBeNull();
});

it('returns a recommendation when sales users exist', function () {
    $salesUser = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    $lead = Lead::factory()->create([
        'assigned_to' => $salesUser->id,
    ]);

    $result = $this->service->getRecommendedAssignee($lead);

    expect($result)->not->toBeNull()
        ->and($result['user']->id)->toBe($salesUser->id);
});

it('gets all recommendations sorted by score', function () {
    // Create multiple sales users with different performance data
    $user1 = User::factory()->create([
        'name' => 'High Performer',
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    $user2 = User::factory()->create([
        'name' => 'Low Performer',
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    // Give user1 better performance data - explicitly assign to user1
    $leads1 = Lead::factory()->count(10)->create([
        'assigned_to' => $user1->id,
        'lead_date' => now(),
        'status' => 'In Progress',
    ]);

    // Create conversions explicitly linking to user1 and existing leads
    foreach ($leads1->take(5) as $lead) {
        Conversion::factory()->create([
            'lead_id' => $lead->id,
            'converted_by' => $user1->id,
            'conversion_date' => now(),
        ]);
    }

    // User2 has no conversions - explicitly assign to user2
    Lead::factory()->count(5)->create([
        'assigned_to' => $user2->id,
        'lead_date' => now(),
        'status' => 'In Progress',
    ]);

    $recommendations = $this->service->getAllRecommendations();

    expect($recommendations)->toHaveCount(2)
        ->and($recommendations->first()['user']->id)->toBe($user1->id);
});

it('excludes inactive users from recommendations', function () {
    User::factory()->create([
        'role' => 'sales_person',
        'is_active' => false,
    ]);

    $activeUser = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    $recommendations = $this->service->getAllRecommendations();

    expect($recommendations)->toHaveCount(1)
        ->and($recommendations->first()['user']->id)->toBe($activeUser->id);
});

it('excludes admin users from recommendations', function () {
    User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $salesUser = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    $recommendations = $this->service->getAllRecommendations();

    expect($recommendations)->toHaveCount(1)
        ->and($recommendations->first()['user']->id)->toBe($salesUser->id);
});

it('calculates and stores performance for all users', function () {
    $user1 = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    $user2 = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    Lead::factory()->count(5)->create([
        'assigned_to' => $user1->id,
        'lead_date' => now(),
    ]);

    $this->service->calculateAllPerformance('monthly');

    expect(SalesPerformance::count())->toBe(2)
        ->and(SalesPerformance::where('user_id', $user1->id)->first())->not->toBeNull()
        ->and(SalesPerformance::where('user_id', $user2->id)->first())->not->toBeNull();
});

it('calculates correct conversion rate', function () {
    $user = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    // 10 leads, 4 conversions = 40% conversion rate
    Lead::factory()->count(10)->create([
        'assigned_to' => $user->id,
        'lead_date' => now(),
    ]);

    Conversion::factory()->count(4)->create([
        'converted_by' => $user->id,
        'conversion_date' => now(),
    ]);

    $this->service->calculateAllPerformance('monthly');

    $performance = SalesPerformance::where('user_id', $user->id)->first();

    // Use toEqual for loose comparison (string vs float)
    expect((float) $performance->conversion_rate)->toEqual(40.00);
});

it('includes workload in recommendations', function () {
    $user = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    // Create active leads
    Lead::factory()->count(5)->create([
        'assigned_to' => $user->id,
        'status' => 'In Progress',
    ]);

    $recommendations = $this->service->getAllRecommendations();

    expect($recommendations->first()['workload'])->toHaveKeys([
        'active_leads',
        'pending_follow_ups',
        'max_leads',
        'capacity_percentage',
    ])
        ->and($recommendations->first()['workload']['active_leads'])->toBe(5);
});

it('uses cached performance data when available', function () {
    $user = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    // Create cached performance
    SalesPerformance::create([
        'user_id' => $user->id,
        'period_type' => 'monthly',
        'period_start' => now()->startOfMonth(),
        'period_end' => now()->endOfMonth(),
        'total_leads' => 100,
        'total_conversions' => 50,
        'conversion_rate' => 50.00,
        'response_rate' => 75.00,
        'follow_up_rate' => 80.00,
        'performance_score' => 85.50,
        'active_leads' => 10,
    ]);

    $recommendations = $this->service->getAllRecommendations();

    // Should use cached score, not recalculate
    expect($recommendations->first()['score'])->toBe(85.50)
        ->and((float) $recommendations->first()['metrics']['conversion_rate'])->toEqual(50.00);
});

it('calculates workload balance correctly', function () {
    $user = User::factory()->create([
        'role' => 'sales_person',
        'is_active' => true,
    ]);

    // Max leads is 20 by default, so 10 active = 50% capacity
    Lead::factory()->count(10)->create([
        'assigned_to' => $user->id,
        'status' => 'In Progress',
    ]);

    $recommendations = $this->service->getAllRecommendations();

    // 10/20 = 50% utilized = 50% capacity available
    expect($recommendations->first()['workload']['capacity_percentage'])->toBe(50.0);
});
