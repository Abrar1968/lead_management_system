<?php

use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
});

describe('Lead Index', function () {
    test('admin can view all leads', function () {
        Lead::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('leads.index'));

        $response->assertOk();
        $response->assertViewIs('leads.index');
    });

    test('sales person can view leads', function () {
        Lead::factory()->count(3)->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)->get(route('leads.index'));

        $response->assertOk();
    });

    test('unauthenticated user cannot view leads', function () {
        $response = $this->get(route('leads.index'));

        $response->assertRedirect(route('login'));
    });
});

describe('Lead Create', function () {
    test('user can view create lead form', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('leads.create'));

        $response->assertOk();
        $response->assertViewIs('leads.create');
    });

    test('user can create a lead', function () {
        $service = \App\Models\Service::factory()->create(['name' => 'Website Development']);

        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345678',
            'email' => 'test@example.com',
            'company_name' => 'Test Company',
            'service_id' => $service->id,
            'service_interested' => $service->name,
            'lead_date' => now()->format('Y-m-d'),
            'priority' => 'High',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'phone_number' => '01712345678',
            'source' => 'WhatsApp',
        ]);
    });

    test('validation fails with missing required fields', function () {
        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), []);

        $response->assertSessionHasErrors(['source', 'phone_number', 'service_id', 'lead_date']);
    });
});

describe('Lead Show', function () {
    test('user can view their assigned lead', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)->get(route('leads.show', $lead));

        $response->assertOk();
        $response->assertViewIs('leads.show');
    });

    test('admin can view any lead', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->admin)->get(route('leads.show', $lead));

        $response->assertOk();
    });
});

describe('Lead Update', function () {
    test('user can update their assigned lead', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->salesPerson)
            ->put(route('leads.update', $lead), [
                'source' => 'Messenger',
                'phone_number' => '01798765432',
                'service_interested' => 'Software',
                'lead_date' => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'source' => 'Messenger',
        ]);
    });
});

describe('Lead Delete', function () {
    test('admin can delete a lead', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->admin)->delete(route('leads.destroy', $lead));

        $response->assertRedirect();
        $this->assertSoftDeleted('leads', ['id' => $lead->id]);
    });
});

describe('Daily Lead View', function () {
    test('user can view daily leads', function () {
        Lead::factory()->count(3)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('leads.daily'));

        $response->assertOk();
        $response->assertViewIs('leads.daily');
    });

    test('user can filter daily leads by date', function () {
        $yesterday = now()->subDay();
        Lead::factory()->count(2)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => $yesterday,
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('leads.daily', ['date' => $yesterday->format('Y-m-d')]));

        $response->assertOk();
    });
});

describe('Monthly Lead View', function () {
    test('user can view monthly leads', function () {
        Lead::factory()->count(5)->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)->get(route('leads.monthly'));

        $response->assertOk();
        $response->assertViewIs('leads.monthly');
    });
});

describe('Repeat Lead Check', function () {
    test('can check for repeat leads by phone number', function () {
        Lead::factory()->create([
            'phone_number' => '01712345678',
            'assigned_to' => $this->salesPerson->id,
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.check-repeat'), ['phone_number' => '01712345678']);

        $response->assertOk();
        $response->assertJson(['is_repeat' => true]);
    });

    test('returns false for new phone number', function () {
        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.check-repeat'), ['phone_number' => '01799999999']);

        $response->assertOk();
        $response->assertJson(['is_repeat' => false]);
    });
});
