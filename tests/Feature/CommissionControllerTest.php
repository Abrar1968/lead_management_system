<?php

use App\Models\Conversion;
use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->salesPerson()->fixedCommission(500)->create();
    $this->admin = User::factory()->admin()->create();
});

describe('Commission Settings Page', function () {
    test('user can view their commission settings', function () {
        $response = $this->actingAs($this->user)->get(route('commission.settings'));

        $response->assertOk();
        $response->assertViewIs('commission.settings');
        $response->assertViewHas('user');
    });

    test('page shows commission breakdown', function () {
        // Create some conversions for the user
        $lead = Lead::factory()->converted()->create(['assigned_to' => $this->user->id]);
        Conversion::factory()->create([
            'lead_id' => $lead->id,
            'converted_by' => $this->user->id,
            'conversion_date' => now(),
            'commission_amount' => 500,
        ]);

        $response = $this->actingAs($this->user)->get(route('commission.settings'));

        $response->assertOk();
        $response->assertViewHas('breakdown');
    });

    test('unauthenticated user cannot view commission settings', function () {
        $response = $this->get(route('commission.settings'));

        $response->assertRedirect(route('login'));
    });
});

describe('Update Commission Settings', function () {
    test('user can update their commission type to fixed', function () {
        $response = $this->actingAs($this->user)
            ->put(route('commission.update'), [
                'commission_type' => 'fixed',
                'commission_rate' => 600,
            ]);

        $response->assertRedirect(route('commission.settings'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'commission_type' => 'fixed',
            'default_commission_rate' => 600,
        ]);
    });

    test('user can update their commission type to percentage', function () {
        $response = $this->actingAs($this->user)
            ->put(route('commission.update'), [
                'commission_type' => 'percentage',
                'commission_rate' => 10,
            ]);

        $response->assertRedirect(route('commission.settings'));

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'commission_type' => 'percentage',
        ]);
    });

    test('validation fails with invalid commission type', function () {
        $response = $this->actingAs($this->user)
            ->put(route('commission.update'), [
                'commission_type' => 'invalid',
                'commission_rate' => 500,
            ]);

        $response->assertSessionHasErrors(['commission_type']);
    });

    test('validation fails with negative commission rate', function () {
        $response = $this->actingAs($this->user)
            ->put(route('commission.update'), [
                'commission_type' => 'fixed',
                'commission_rate' => -100,
            ]);

        $response->assertSessionHasErrors(['commission_rate']);
    });
});

describe('Admin Commission Management', function () {
    test('admin can view commission management dashboard', function () {
        $response = $this->actingAs($this->admin)->get(route('admin.commissions.index'));

        $response->assertOk();
        $response->assertViewIs('commission.admin-index');
        $response->assertViewHas(['usersWithStats', 'month', 'year']);
    });

    test('sales person cannot view commission management dashboard', function () {
        $response = $this->actingAs($this->user)->get(route('admin.commissions.index'));

        $response->assertForbidden();
    });

    test('admin can view edit form for a user', function () {
        $salesPerson = User::factory()->salesPerson()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.commissions.edit', $salesPerson));

        $response->assertOk();
        $response->assertViewIs('commission.admin-edit');
        $response->assertViewHas('user');
    });

    test('admin can update a user commission settings', function () {
        $salesPerson = User::factory()->salesPerson()->fixedCommission(500)->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.commissions.update', $salesPerson), [
                'commission_type' => 'percentage',
                'default_commission_rate' => 15,
            ]);

        $response->assertRedirect(route('admin.commissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $salesPerson->id,
            'commission_type' => 'percentage',
            'default_commission_rate' => 15,
        ]);
    });

    test('admin update validates commission rate for percentage type', function () {
        $salesPerson = User::factory()->salesPerson()->create();

        $response = $this->actingAs($this->admin)
            ->put(route('admin.commissions.update', $salesPerson), [
                'commission_type' => 'percentage',
                'default_commission_rate' => 150, // Invalid: exceeds 100
            ]);

        $response->assertSessionHasErrors(['default_commission_rate']);
    });

    test('sales person cannot update other users commission settings', function () {
        $otherUser = User::factory()->salesPerson()->create();

        $response = $this->actingAs($this->user)
            ->put(route('admin.commissions.update', $otherUser), [
                'commission_type' => 'fixed',
                'default_commission_rate' => 1000,
            ]);

        $response->assertForbidden();
    });
});
