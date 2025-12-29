<?php

use App\Models\CommissionType;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->salesPerson = User::factory()->create(['role' => 'sales_person']);
});

// ========================
// INDEX TESTS
// ========================

test('admin can view commission types index', function () {
    CommissionType::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)
        ->get(route('admin.commission-types.index'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.commission-types.index');
    $response->assertViewHas('commissionTypes');
});

test('sales person cannot view commission types index', function () {
    $response = $this->actingAs($this->salesPerson)
        ->get(route('admin.commission-types.index'));

    $response->assertForbidden();
});

test('unauthenticated user is redirected from commission types', function () {
    $response = $this->get(route('admin.commission-types.index'));

    $response->assertRedirect(route('login'));
});

// ========================
// CREATE TESTS
// ========================

test('admin can view create commission type form', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.commission-types.create'));

    $response->assertSuccessful();
    $response->assertViewIs('admin.commission-types.create');
});

test('admin can create a fixed commission type', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'name' => 'Sales Commission',
            'description' => 'Standard sales commission',
            'calculation_type' => 'fixed',
            'default_rate' => 500,
            'is_active' => true,
            'is_default' => false,
        ]);

    $response->assertRedirect(route('admin.commission-types.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('commission_types', [
        'name' => 'Sales Commission',
        'calculation_type' => 'fixed',
        'default_rate' => 500,
        'slug' => 'sales-commission',
    ]);
});

test('admin can create a percentage commission type', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'name' => 'Performance Bonus',
            'calculation_type' => 'percentage',
            'default_rate' => 10.5,
            'is_active' => true,
        ]);

    $response->assertRedirect(route('admin.commission-types.index'));

    $this->assertDatabaseHas('commission_types', [
        'name' => 'Performance Bonus',
        'calculation_type' => 'percentage',
        'default_rate' => 10.5,
    ]);
});

test('commission type name is required', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'calculation_type' => 'fixed',
            'default_rate' => 500,
        ]);

    $response->assertSessionHasErrors('name');
});

test('calculation type is required', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'name' => 'Test Commission',
            'default_rate' => 500,
        ]);

    $response->assertSessionHasErrors('calculation_type');
});

test('percentage rate cannot exceed 100', function () {
    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'name' => 'High Rate',
            'calculation_type' => 'percentage',
            'default_rate' => 150,
        ]);

    $response->assertSessionHasErrors('default_rate');
});

test('commission type name must be unique', function () {
    CommissionType::factory()->create(['name' => 'Existing Commission']);

    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.store'), [
            'name' => 'Existing Commission',
            'calculation_type' => 'fixed',
            'default_rate' => 500,
        ]);

    $response->assertSessionHasErrors('name');
});

// ========================
// EDIT/UPDATE TESTS
// ========================

test('admin can view edit commission type form', function () {
    $commissionType = CommissionType::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('admin.commission-types.edit', $commissionType));

    $response->assertSuccessful();
    $response->assertViewIs('admin.commission-types.edit');
    $response->assertViewHas('commissionType');
});

test('admin can update a commission type', function () {
    $commissionType = CommissionType::factory()->create([
        'name' => 'Old Name',
        'default_rate' => 100,
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.commission-types.update', $commissionType), [
            'name' => 'New Name',
            'calculation_type' => 'fixed',
            'default_rate' => 750,
            'is_active' => true,
        ]);

    $response->assertRedirect(route('admin.commission-types.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('commission_types', [
        'id' => $commissionType->id,
        'name' => 'New Name',
        'default_rate' => 750,
    ]);
});

// ========================
// DELETE TESTS
// ========================

test('admin can delete a commission type with no users', function () {
    $commissionType = CommissionType::factory()->create();

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.commission-types.destroy', $commissionType));

    $response->assertRedirect(route('admin.commission-types.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('commission_types', [
        'id' => $commissionType->id,
    ]);
});

test('admin cannot delete a commission type with assigned users', function () {
    $commissionType = CommissionType::factory()->create();
    $commissionType->users()->attach($this->salesPerson->id);

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.commission-types.destroy', $commissionType));

    $response->assertRedirect();
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('commission_types', [
        'id' => $commissionType->id,
    ]);
});

// ========================
// USER ASSIGNMENT TESTS
// ========================

test('admin can view users page for a commission type', function () {
    $commissionType = CommissionType::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('admin.commission-types.users', $commissionType));

    $response->assertSuccessful();
    $response->assertViewIs('admin.commission-types.users');
    $response->assertViewHas(['commissionType', 'availableUsers']);
});

test('admin can assign a user to a commission type', function () {
    $commissionType = CommissionType::factory()->create();

    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.assign', $commissionType), [
            'user_id' => $this->salesPerson->id,
            'is_primary' => true,
        ]);

    $response->assertRedirect(route('admin.commission-types.users', $commissionType));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('user_commission_types', [
        'user_id' => $this->salesPerson->id,
        'commission_type_id' => $commissionType->id,
        'is_primary' => true,
    ]);
});

test('admin can assign a user with custom rate', function () {
    $commissionType = CommissionType::factory()->fixed()->create(['default_rate' => 500]);

    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.assign', $commissionType), [
            'user_id' => $this->salesPerson->id,
            'custom_rate' => 750,
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('user_commission_types', [
        'user_id' => $this->salesPerson->id,
        'commission_type_id' => $commissionType->id,
        'custom_rate' => 750,
    ]);
});

test('admin cannot assign same user twice to same commission type', function () {
    $commissionType = CommissionType::factory()->create();
    $commissionType->users()->attach($this->salesPerson->id);

    $response = $this->actingAs($this->admin)
        ->post(route('admin.commission-types.assign', $commissionType), [
            'user_id' => $this->salesPerson->id,
        ]);

    $response->assertSessionHasErrors('user_id');
});

test('admin can remove user from commission type', function () {
    $commissionType = CommissionType::factory()->create();
    $commissionType->users()->attach($this->salesPerson->id);

    $response = $this->actingAs($this->admin)
        ->delete(route('admin.commission-types.remove', [$commissionType, $this->salesPerson]));

    $response->assertRedirect(route('admin.commission-types.users', $commissionType));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('user_commission_types', [
        'user_id' => $this->salesPerson->id,
        'commission_type_id' => $commissionType->id,
    ]);
});

// ========================
// MODEL TESTS
// ========================

test('commission type generates slug automatically', function () {
    $commissionType = CommissionType::create([
        'name' => 'My New Commission Type',
        'calculation_type' => 'fixed',
        'default_rate' => 100,
    ]);

    expect($commissionType->slug)->toBe('my-new-commission-type');
});

test('commission type calculates fixed amount correctly', function () {
    $commissionType = CommissionType::factory()->fixed()->create(['default_rate' => 500]);

    $amount = $commissionType->calculateCommission(10000); // deal value doesn't matter for fixed

    expect($amount)->toBe(500.0);
});

test('commission type calculates percentage correctly', function () {
    $commissionType = CommissionType::factory()->percentage()->create(['default_rate' => 10]);

    $amount = $commissionType->calculateCommission(10000);

    expect($amount)->toBe(1000.0);
});

test('commission type uses custom rate when user has one', function () {
    $commissionType = CommissionType::factory()->fixed()->create(['default_rate' => 500]);
    $commissionType->users()->attach($this->salesPerson->id, ['custom_rate' => 750]);

    $effectiveRate = $commissionType->getEffectiveRateForUser($this->salesPerson);

    expect($effectiveRate)->toBe(750.0);
});

test('commission type uses default rate when user has no custom rate', function () {
    $commissionType = CommissionType::factory()->fixed()->create(['default_rate' => 500]);
    $commissionType->users()->attach($this->salesPerson->id, ['custom_rate' => null]);

    $effectiveRate = $commissionType->getEffectiveRateForUser($this->salesPerson);

    expect($effectiveRate)->toBe(500.0);
});

test('active scope returns only active commission types', function () {
    CommissionType::factory()->count(3)->create(['is_active' => true]);
    CommissionType::factory()->count(2)->create(['is_active' => false]);

    $activeTypes = CommissionType::active()->get();

    expect($activeTypes)->toHaveCount(3);
});

test('default scope returns only default commission types', function () {
    CommissionType::factory()->count(2)->create(['is_default' => false]);
    CommissionType::factory()->default()->create();

    $defaultTypes = CommissionType::default()->get();

    expect($defaultTypes)->toHaveCount(1);
});

// ========================
// USER MODEL TESTS
// ========================

test('user can have multiple commission types', function () {
    $type1 = CommissionType::factory()->create();
    $type2 = CommissionType::factory()->create();

    $this->salesPerson->commissionTypes()->attach([
        $type1->id => ['is_primary' => true],
        $type2->id => ['is_primary' => false],
    ]);

    expect($this->salesPerson->commissionTypes)->toHaveCount(2);
});

test('user primary commission type returns correct type', function () {
    $type1 = CommissionType::factory()->create();
    $type2 = CommissionType::factory()->create();

    $this->salesPerson->commissionTypes()->attach([
        $type1->id => ['is_primary' => false],
        $type2->id => ['is_primary' => true],
    ]);

    $primary = $this->salesPerson->primaryCommissionType();

    expect($primary->id)->toBe($type2->id);
});

test('user get effective commission rate uses primary commission type', function () {
    $commissionType = CommissionType::factory()->fixed()->create(['default_rate' => 600]);
    $this->salesPerson->commissionTypes()->attach($commissionType->id, [
        'is_primary' => true,
        'custom_rate' => 800,
    ]);

    $rate = $this->salesPerson->getEffectiveCommissionRate();

    expect($rate)->toBe(800.0);
});

test('user get effective commission rate falls back to legacy field', function () {
    $this->salesPerson->update(['default_commission_rate' => 450]);

    $rate = $this->salesPerson->getEffectiveCommissionRate();

    expect($rate)->toBe(450.0);
});

test('user get effective commission type uses primary commission type', function () {
    $commissionType = CommissionType::factory()->percentage()->create();
    $this->salesPerson->commissionTypes()->attach($commissionType->id, ['is_primary' => true]);

    $type = $this->salesPerson->getEffectiveCommissionType();

    expect($type)->toBe('percentage');
});

test('user get effective commission type falls back to legacy field', function () {
    $this->salesPerson->update(['commission_type' => 'fixed']);

    $type = $this->salesPerson->getEffectiveCommissionType();

    expect($type)->toBe('fixed');
});
