<?php

use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
});

describe('Reports Page', function () {
    test('user can view reports page', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewIs('reports.index');
    });

    test('shows monthly statistics', function () {
        Lead::factory()->count(3)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('totalLeads');
    });

    test('admin sees all data', function () {
        // Create leads for different users
        Lead::factory()->count(3)->create(['lead_date' => now()]);

        $response = $this->actingAs($this->admin)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('isAdmin', true);
    });

    test('sales person sees only their data', function () {
        // Create leads for another user
        $otherUser = User::factory()->salesPerson()->create();
        Lead::factory()->count(2)->create([
            'assigned_to' => $otherUser->id,
            'lead_date' => now(),
        ]);

        // Create leads for this user
        Lead::factory()->count(3)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('isAdmin', false);
    });

    test('can filter by month', function () {
        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', ['month' => now()->format('Y-m')]));

        $response->assertOk();
    });

    test('shows source breakdown', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'source' => 'WhatsApp',
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('sourceBreakdown');
    });

    test('shows service breakdown', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'service_interested' => 'Website',
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('serviceBreakdown');
    });

    test('admin sees top performers', function () {
        $response = $this->actingAs($this->admin)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('topPerformers');
    });

    test('unauthenticated user cannot view reports', function () {
        $response = $this->get(route('reports.index'));

        $response->assertRedirect(route('login'));
    });
});
