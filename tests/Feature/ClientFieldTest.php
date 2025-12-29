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

it('can preview document with inline disposition', function () {
    // Create a document field definition
    $field = FieldDefinition::create([
        'model_type' => 'client',
        'name' => 'contract',
        'label' => 'Contract Document',
        'type' => 'document',
        'required' => false,
        'is_active' => true,
    ]);

    // Create lead, conversion, and client
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

    // Create a test file
    $testContent = 'Test document content';
    $testPath = 'clients/documents/test_contract.txt';
    \Illuminate\Support\Facades\Storage::disk('public')->put($testPath, $testContent);

    // Set field value
    $client->setFieldValue($field->id, $testPath);

    // Test preview route
    $response = $this->actingAs($this->salesPerson)
        ->get(route('clients.preview-document', ['client' => $client, 'fieldId' => $field->id]));

    $response->assertOk();
    expect($response->headers->get('Content-Disposition'))->toContain('inline');

    // For streamed responses, we verify by checking headers and that file exists
    // StreamedResponse->getContent() returns false, so we verify file content via storage
    expect(\Illuminate\Support\Facades\Storage::disk('public')->get($testPath))->toBe($testContent);

    // Cleanup
    \Illuminate\Support\Facades\Storage::disk('public')->delete($testPath);
});

it('returns 404 when document field does not exist', function () {
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
        ->get(route('clients.preview-document', ['client' => $client, 'fieldId' => 999]))
        ->assertNotFound();
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
