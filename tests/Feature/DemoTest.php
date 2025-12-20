<?php

use App\Models\Demo;
use App\Models\FieldDefinition;
use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->salesPerson = User::factory()->create(['role' => 'sales_person']);
});

test('admin can view demos index', function () {
    $demo = Demo::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('demos.index'));

    $response->assertSuccessful();
    $response->assertViewHas('demos');
});

test('sales person only sees their own demos', function () {
    $ownDemo = Demo::factory()->create(['created_by' => $this->salesPerson->id]);
    $otherDemo = Demo::factory()->create(['created_by' => $this->admin->id]);

    $response = $this->actingAs($this->salesPerson)
        ->get(route('demos.index'));

    $response->assertSuccessful();
    $demos = $response->viewData('demos');

    expect($demos->pluck('id')->toArray())->toContain($ownDemo->id);
    expect($demos->pluck('id')->toArray())->not->toContain($otherDemo->id);
});

test('user can create a demo', function () {
    $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

    $response = $this->actingAs($this->salesPerson)
        ->post(route('demos.store'), [
            'lead_id' => $lead->id,
            'title' => 'CRM Demo for ABC Company',
            'description' => 'Demo objectives and features',
            'demo_date' => now()->addDays(2)->format('Y-m-d'),
            'demo_time' => '14:00',
            'type' => 'Online',
            'meeting_link' => 'https://meet.google.com/abc-def-ghi',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('demos', [
        'title' => 'CRM Demo for ABC Company',
        'lead_id' => $lead->id,
        'created_by' => $this->salesPerson->id,
        'type' => 'Online',
        'status' => 'Scheduled',
    ]);
});

test('user can update a demo status', function () {
    $demo = Demo::factory()->create([
        'created_by' => $this->salesPerson->id,
        'status' => 'Scheduled',
    ]);

    $response = $this->actingAs($this->salesPerson)
        ->put(route('demos.update', $demo), [
            'title' => $demo->title,
            'demo_date' => $demo->demo_date->format('Y-m-d'),
            'type' => $demo->type,
            'status' => 'Completed',
            'outcome_notes' => 'Client loved the demo and is ready to proceed',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('demos', [
        'id' => $demo->id,
        'status' => 'Completed',
        'outcome_notes' => 'Client loved the demo and is ready to proceed',
    ]);
});

test('demo can have dynamic fields', function () {
    $field = FieldDefinition::create([
        'model_type' => 'demo',
        'name' => 'presenter_name',
        'label' => 'Presenter Name',
        'type' => 'text',
        'required' => false,
        'order' => 1,
        'is_active' => true,
    ]);

    $demo = Demo::factory()->create(['created_by' => $this->salesPerson->id]);
    $demo->setFieldValue($field->id, 'John Smith');

    expect($demo->getFieldValue($field->id))->toBe('John Smith');
    expect($demo->fieldValues()->count())->toBe(1);
});
