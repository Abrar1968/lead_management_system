<?php

use App\Models\Lead;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->salesPerson = User::factory()->create(['role' => 'sales_person']);
});

it('returns empty meetings when no meetings exist', function () {
    $this->actingAs($this->admin)
        ->getJson('/notifications/check')
        ->assertOk()
        ->assertJson([
            'alert' => false,
            'meetings' => [],
            'total_today' => 0,
        ]);
});

it('returns today pending meetings for admin', function () {
    $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

    Meeting::create([
        'lead_id' => $lead->id,
        'meeting_date' => now()->format('Y-m-d'),
        'meeting_time' => now()->addMinutes(30)->format('H:i'),
        'meeting_type' => 'Online',
        'outcome' => 'Pending',
    ]);

    $this->actingAs($this->admin)
        ->getJson('/notifications/check?login_check=1')
        ->assertOk()
        ->assertJsonPath('alert', true)
        ->assertJsonPath('total_today', 1)
        ->assertJsonCount(1, 'meetings');
});

it('returns only assigned meetings for sales person', function () {
    $otherSalesPerson = User::factory()->create(['role' => 'sales_person']);

    $assignedLead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);
    $otherLead = Lead::factory()->create(['assigned_to' => $otherSalesPerson->id]);

    Meeting::create([
        'lead_id' => $assignedLead->id,
        'meeting_date' => now()->format('Y-m-d'),
        'meeting_time' => now()->addMinutes(30)->format('H:i'),
        'meeting_type' => 'Online',
        'outcome' => 'Pending',
    ]);

    Meeting::create([
        'lead_id' => $otherLead->id,
        'meeting_date' => now()->format('Y-m-d'),
        'meeting_time' => now()->addMinutes(45)->format('H:i'),
        'meeting_type' => 'Physical',
        'outcome' => 'Pending',
    ]);

    $this->actingAs($this->salesPerson)
        ->getJson('/notifications/check?login_check=1')
        ->assertOk()
        ->assertJsonPath('total_today', 1)
        ->assertJsonCount(1, 'meetings');
});

it('does not return completed meetings', function () {
    $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

    Meeting::create([
        'lead_id' => $lead->id,
        'meeting_date' => now()->format('Y-m-d'),
        'meeting_time' => now()->addMinutes(30)->format('H:i'),
        'meeting_type' => 'Online',
        'outcome' => 'Positive', // Completed
    ]);

    $this->actingAs($this->admin)
        ->getJson('/notifications/check')
        ->assertOk()
        ->assertJsonPath('alert', false)
        ->assertJsonPath('total_today', 0);
});

it('can dismiss login alert', function () {
    $this->actingAs($this->admin)
        ->postJson('/notifications/dismiss')
        ->assertOk()
        ->assertJson(['success' => true]);
});
