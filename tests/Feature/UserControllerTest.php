<?php

use App\Models\Lead;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
});

describe('User Index', function () {
    test('admin can view users list', function () {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertOk();
        $response->assertViewIs('users.index');
    });

    test('sales person cannot access users list', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('users.index'));

        $response->assertForbidden();
    });

    test('can filter users by role', function () {
        $response = $this->actingAs($this->admin)
            ->get(route('users.index', ['role' => 'admin']));

        $response->assertOk();
    });

    test('can search users by name', function () {
        User::factory()->create(['name' => 'Test User']);

        $response = $this->actingAs($this->admin)
            ->get(route('users.index', ['search' => 'Test']));

        $response->assertOk();
    });
});

describe('User Create', function () {
    test('admin can view create user form', function () {
        $response = $this->actingAs($this->admin)->get(route('users.create'));

        $response->assertOk();
        $response->assertViewIs('users.create');
    });

    test('admin can create a new user', function () {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'sales_person',
            'commission_type' => 'fixed',
            'default_commission_rate' => 500,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('users.store'), $userData);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role' => 'sales_person',
        ]);
    });

    test('validation fails with duplicate email', function () {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->admin)
            ->post(route('users.store'), [
                'name' => 'New User',
                'email' => 'existing@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'sales_person',
                'commission_type' => 'fixed',
                'default_commission_rate' => 500,
            ]);

        $response->assertSessionHasErrors(['email']);
    });
});

describe('User Show', function () {
    test('admin can view user performance page', function () {
        $response = $this->actingAs($this->admin)
            ->get(route('users.show', $this->salesPerson));

        $response->assertOk();
        $response->assertViewIs('users.show');
        $response->assertViewHas('user');
    });

    test('shows monthly statistics', function () {
        $lead = Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('users.show', $this->salesPerson));

        $response->assertViewHas(['monthlyLeads', 'monthlyConversions', 'monthlyCommission']);
    });
});

describe('User Edit', function () {
    test('admin can view edit user form', function () {
        $response = $this->actingAs($this->admin)
            ->get(route('users.edit', $this->salesPerson));

        $response->assertOk();
        $response->assertViewIs('users.edit');
    });

    test('admin can update a user', function () {
        $response = $this->actingAs($this->admin)
            ->put(route('users.update', $this->salesPerson), [
                'name' => 'Updated Name',
                'email' => $this->salesPerson->email,
                'role' => 'admin',
                'commission_type' => 'percentage',
                'default_commission_rate' => 15,
            ]);

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $this->salesPerson->id,
            'name' => 'Updated Name',
            'role' => 'admin',
        ]);
    });
});

describe('User Delete', function () {
    test('admin can view delete confirmation page', function () {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('users.delete', $userToDelete));

        $response->assertOk();
        $response->assertViewIs('users.delete');
        $response->assertViewHas('user');
    });

    test('admin can delete a user without leads', function () {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('users.destroy', $userToDelete));

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    });

    test('cannot delete user with assigned leads', function () {
        Lead::factory()->create(['assigned_to' => $this->salesPerson->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('users.destroy', $this->salesPerson));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->salesPerson->id]);
    });

    test('user cannot delete themselves', function () {
        $response = $this->actingAs($this->admin)
            ->delete(route('users.destroy', $this->admin));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    test('sales person cannot access delete page', function () {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->salesPerson)
            ->get(route('users.delete', $userToDelete));

        $response->assertForbidden();
    });
});
