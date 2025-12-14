<?php

use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
});

describe('Dashboard Page', function () {
    test('authenticated user can view dashboard', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard');
    });

    test('dashboard shows stats', function () {
        Lead::factory()->count(3)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('dashboard'));

        $response->assertViewHas('stats');
    });

    test('dashboard shows pending follow-ups', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);
        FollowUp::factory()->pending()->create([
            'lead_id' => $lead->id,
            'created_by' => $this->salesPerson->id,
            'follow_up_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('todayFollowUps');
    });

    test('unauthenticated user redirected to login', function () {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    });

    test('admin sees all stats', function () {
        // Create leads for different users
        Lead::factory()->count(2)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('stats');
    });
});
