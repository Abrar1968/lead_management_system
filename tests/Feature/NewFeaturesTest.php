<?php

use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\Service;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
    $this->service = Service::factory()->create(['name' => 'Website Development']);
});

describe('Lead Creation with Status Field', function () {
    test('user can create lead with status field', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345678',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'status' => 'Qualified',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'phone_number' => '01712345678',
            'status' => 'Qualified',
        ]);
    });

    test('lead defaults to New status when not provided', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345679',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        $this->assertDatabaseHas('leads', [
            'phone_number' => '01712345679',
            'status' => 'New',
        ]);
    });

    test('invalid status fails validation', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345680',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'status' => 'InvalidStatus',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertSessionHasErrors(['status']);
    });

    test('all valid statuses are accepted', function () {
        $statuses = ['New', 'Contacted', 'Qualified', 'Negotiation', 'Converted', 'Lost'];

        foreach ($statuses as $index => $status) {
            $leadData = [
                'source' => 'WhatsApp',
                'client_name' => 'Test Client',
                'phone_number' => '0171234'.str_pad($index, 4, '0', STR_PAD_LEFT),
                'service_id' => $this->service->id,
                'service_interested' => $this->service->name,
                'lead_date' => now()->format('Y-m-d'),
                'status' => $status,
            ];

            $response = $this->actingAs($this->salesPerson)
                ->post(route('leads.store'), $leadData);

            $response->assertSessionDoesntHaveErrors(['status']);
        }
    });
});

describe('Lead Creation with Initial Response Field', function () {
    test('user can create lead with initial response', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345681',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'initial_response' => 'Interested',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        // Lead should be created
        $lead = Lead::where('phone_number', '01712345681')->first();
        expect($lead)->not->toBeNull();

        // LeadContact should be created
        $this->assertDatabaseHas('lead_contacts', [
            'lead_id' => $lead->id,
            'response_status' => 'Interested',
            'caller_id' => $this->salesPerson->id,
        ]);
    });

    test('initial response creates contact and updates status to Contacted', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345682',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'status' => 'New',
            'initial_response' => 'Interested',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        // Lead status should be updated to Contacted
        $this->assertDatabaseHas('leads', [
            'phone_number' => '01712345682',
            'status' => 'Contacted',
        ]);
    });

    test('no contact created when initial response is empty', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345683',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'initial_response' => '',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        $lead = Lead::where('phone_number', '01712345683')->first();
        expect($lead)->not->toBeNull();

        // No LeadContact should be created
        expect(LeadContact::where('lead_id', $lead->id)->count())->toBe(0);
    });

    test('invalid initial response fails validation', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345684',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'initial_response' => 'InvalidResponse',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertSessionHasErrors(['initial_response']);
    });

    test('all valid response statuses are accepted', function () {
        $responses = ['Interested', '50%', 'Yes', 'Call Later', 'No Response', 'No', 'Phone off'];

        foreach ($responses as $index => $responseStatus) {
            $leadData = [
                'source' => 'WhatsApp',
                'client_name' => 'Test Client',
                'phone_number' => '0171200'.str_pad($index, 4, '0', STR_PAD_LEFT),
                'service_id' => $this->service->id,
                'service_interested' => $this->service->name,
                'lead_date' => now()->format('Y-m-d'),
                'initial_response' => $responseStatus,
            ];

            $response = $this->actingAs($this->salesPerson)
                ->post(route('leads.store'), $leadData);

            $response->assertSessionDoesntHaveErrors(['initial_response']);
        }
    });

    test('initial remarks are saved in contact notes', function () {
        $leadData = [
            'source' => 'WhatsApp',
            'client_name' => 'Test Client',
            'phone_number' => '01712345685',
            'service_id' => $this->service->id,
            'service_interested' => $this->service->name,
            'lead_date' => now()->format('Y-m-d'),
            'initial_response' => 'Interested',
            'initial_remarks' => 'Customer wants callback tomorrow',
        ];

        $response = $this->actingAs($this->salesPerson)
            ->post(route('leads.store'), $leadData);

        $response->assertRedirect();

        $lead = Lead::where('phone_number', '01712345685')->first();

        $this->assertDatabaseHas('lead_contacts', [
            'lead_id' => $lead->id,
            'notes' => 'Customer wants callback tomorrow',
        ]);
    });
});

describe('Reports Period Selector', function () {
    test('can view reports with daily period', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'daily',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertViewHas('period', 'daily');
        $response->assertViewHas('periodLabel');
    });

    test('can view reports with weekly period', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'weekly',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertViewHas('period', 'weekly');
        $response->assertViewHas('chartData');
    });

    test('can view reports with monthly period', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'monthly',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertViewHas('period', 'monthly');
    });

    test('can view reports with yearly period', function () {
        Lead::factory()->create([
            'assigned_to' => $this->salesPerson->id,
            'lead_date' => now(),
        ]);

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'yearly',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertViewHas('period', 'yearly');
        $response->assertViewHas('chartData');
    });

    test('default period is monthly', function () {
        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewHas('period', 'monthly');
    });

    test('backwards compatibility with month parameter', function () {
        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', ['month' => now()->format('Y-m')]));

        $response->assertOk();
        $response->assertViewHas('month');
    });

    test('daily period shows correct date label', function () {
        $testDate = '2025-12-15';

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'daily',
                'date' => $testDate,
            ]));

        $response->assertOk();
        $response->assertViewHas('periodLabel', 'December 15, 2025');
    });

    test('yearly period shows year label', function () {
        $testDate = '2025-06-15';

        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'yearly',
                'date' => $testDate,
            ]));

        $response->assertOk();
        $response->assertViewHas('periodLabel', '2025');
    });

    test('yearly period chart has 12 months', function () {
        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.index', [
                'period' => 'yearly',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $chartData = $response->viewData('chartData');
        expect(count($chartData))->toBe(12);
    });

    test('print route works with period parameter', function () {
        $response = $this->actingAs($this->salesPerson)
            ->get(route('reports.print', [
                'period' => 'weekly',
                'date' => now()->format('Y-m-d'),
            ]));

        $response->assertOk();
        $response->assertViewIs('reports.print');
    });
});

describe('Lead Create View Renders Correctly', function () {
    test('create view shows status dropdown', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('leads.create'));

        $response->assertOk();
        $response->assertSee('id="status"', false);
        $response->assertSee('value="New"', false);
        $response->assertSee('value="Contacted"', false);
        $response->assertSee('value="Qualified"', false);
    });

    test('create view shows initial response dropdown', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('leads.create'));

        $response->assertOk();
        $response->assertSee('id="initial_response"', false);
        $response->assertSee('-- No Initial Contact --');
        $response->assertSee('value="Interested"', false);
    });
});

describe('Reports View Renders Correctly', function () {
    test('reports view shows period selector', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('reports.index'));

        $response->assertOk();
        $response->assertSee('Daily');
        $response->assertSee('Weekly');
        $response->assertSee('Monthly');
        $response->assertSee('Yearly');
    });
});
