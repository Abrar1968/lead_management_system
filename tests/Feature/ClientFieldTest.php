<?php

use App\Models\ClientDetail;
use App\Models\Conversion;
use App\Models\FieldDefinition;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->salesPerson = User::factory()->create(['role' => 'sales_person']);
});

it('creates client detail when conversion is created', function () {
    $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id, 'status' => 'New']);

    $this->actingAs($this->salesPerson)
        ->post(route('conversions.store', $lead), [
            'deal_value' => 10000,
            'conversion_date' => now()->format('Y-m-d'),
        ]);

    expect(Conversion::where('lead_id', $lead->id)->exists())->toBeTrue();

    $conversion = Conversion::where('lead_id', $lead->id)->first();
    expect(ClientDetail::where('conversion_id', $conversion->id)->exists())->toBeTrue();
});

it('admin can create field definition', function () {
    $this->actingAs($this->admin)
        ->post(route('field-definitions.store'), [
            'model_type' => 'client',
            'name' => 'company_website',
            'label' => 'Company Website',
            'type' => 'link',
            'required' => false,
        ]);

    expect(FieldDefinition::where('name', 'company_website')->exists())->toBeTrue();
});

it('clients index page loads for authenticated user', function () {
    $this->actingAs($this->salesPerson)
        ->get(route('clients.index'))
        ->assertOk();
});

it('client show page displays dynamic fields', function () {
    // Create a field definition
    $field = FieldDefinition::create([
        'model_type' => 'client',
        'name' => 'test_field',
        'label' => 'Test Field',
        'type' => 'text',
        'required' => false,
        'is_active' => true,
    ]);

    // Create lead and conversion
    $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);
    $conversion = Conversion::create([
        'lead_id' => $lead->id,
        'converted_by' => $this->salesPerson->id,
        'conversion_date' => now(),
        'deal_value' => 5000,
        'commission_rate_used' => 10,
        'commission_type_used' => 'percentage',
        'commission_amount' => 500,
        'package_plan' => 'Standard',
    ]);
    $client = ClientDetail::create(['conversion_id' => $conversion->id]);

    $this->actingAs($this->salesPerson)
        ->get(route('clients.show', $client))
        ->assertOk()
        ->assertSee('Test Field');
});

it('field definition requires unique name per model type', function () {
    FieldDefinition::create([
        'model_type' => 'client',
        'name' => 'unique_field',
        'label' => 'Unique Field',
        'type' => 'text',
    ]);

    $this->actingAs($this->admin)
        ->post(route('field-definitions.store'), [
            'model_type' => 'client',
            'name' => 'unique_field',
            'label' => 'Another Label',
            'type' => 'text',
        ])
        ->assertSessionHasErrors('name');
});
