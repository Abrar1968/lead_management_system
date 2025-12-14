<?php

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->salesPerson = User::factory()->salesPerson()->fixedCommission(500)->create();
    $this->admin = User::factory()->admin()->create();
});

describe('Conversion Create Page', function () {
    test('user can view conversion form for their lead', function () {
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('conversions.create', $lead));

        $response->assertOk();
        $response->assertViewIs('leads.convert');
        $response->assertViewHas('lead');
    });

    test('admin can view conversion form for any lead', function () {
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('conversions.create', $lead));

        $response->assertOk();
    });

    test('user cannot convert another users lead', function () {
        $otherUser = User::factory()->salesPerson()->create();
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $otherUser->id]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('conversions.create', $lead));

        $response->assertForbidden();
    });

    test('already converted lead redirects back', function () {
        $lead = Lead::factory()->converted()->create(['assigned_to' => $this->salesPerson->id]);
        Conversion::factory()->create(['lead_id' => $lead->id, 'converted_by' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('conversions.create', $lead));

        $response->assertRedirect();
    });
});

describe('Store Conversion', function () {
    test('user can convert their lead with fixed commission', function () {
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->post(route('conversions.store', $lead), [
                'deal_value' => 50000,
                'conversion_date' => now()->format('Y-m-d'),
                'notes' => 'Test conversion notes',
            ]);

        $response->assertRedirect(route('leads.show', $lead));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('conversions', [
            'lead_id' => $lead->id,
            'converted_by' => $this->salesPerson->id,
            'deal_value' => 50000,
            'commission_amount' => 500, // Fixed commission
        ]);

        // Lead status should be updated
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'Converted',
        ]);
    });

    test('percentage commission is calculated correctly', function () {
        $percentUser = User::factory()->salesPerson()->percentageCommission(10)->create();
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $percentUser->id]);

        $response = $this->actingAs($percentUser)
            ->post(route('conversions.store', $lead), [
                'deal_value' => 50000,
                'conversion_date' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('conversions', [
            'lead_id' => $lead->id,
            'commission_amount' => 5000, // 10% of 50000
            'commission_type_used' => 'percentage',
            'commission_rate_used' => 10,
        ]);
    });

    test('validation fails with missing deal value', function () {
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->post(route('conversions.store', $lead), [
                'conversion_date' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['deal_value']);
    });

    test('validation fails with future conversion date', function () {
        $lead = Lead::factory()->newStatus()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->post(route('conversions.store', $lead), [
                'deal_value' => 50000,
                'conversion_date' => now()->addDay()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['conversion_date']);
    });

    test('cannot convert already converted lead', function () {
        $lead = Lead::factory()->converted()->create(['assigned_to' => $this->salesPerson->id]);
        Conversion::factory()->create(['lead_id' => $lead->id]);

        $response = $this->actingAs($this->salesPerson)
            ->post(route('conversions.store', $lead), [
                'deal_value' => 50000,
                'conversion_date' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });
});
