<?php

use App\Models\ExtraCommission;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->salesPerson = User::factory()->salesPerson()->create();
});

describe('Extra Commission Index', function () {
    test('admin can view extra commissions list', function () {
        ExtraCommission::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('extra-commissions.index'));

        $response->assertOk();
        $response->assertViewIs('admin.extra-commissions.index');
    });

    test('sales person cannot access extra commissions list', function () {
        $response = $this->actingAs($this->salesPerson)->get(route('extra-commissions.index'));

        $response->assertForbidden();
    });

    test('can filter by status', function () {
        ExtraCommission::factory()->pending()->create();
        ExtraCommission::factory()->approved()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('extra-commissions.index', ['status' => 'Pending']));

        $response->assertOk();
    });
});

describe('Extra Commission Create', function () {
    test('admin can view create form', function () {
        $response = $this->actingAs($this->admin)->get(route('extra-commissions.create'));

        $response->assertOk();
        $response->assertViewIs('admin.extra-commissions.create');
    });

    test('admin can create extra commission', function () {
        $response = $this->actingAs($this->admin)
            ->post(route('extra-commissions.store'), [
                'user_id' => $this->salesPerson->id,
                'commission_type' => 'Bonus',
                'amount' => 1000,
                'date_earned' => now()->format('Y-m-d'),
                'description' => 'Performance bonus',
            ]);

        $response->assertRedirect(route('extra-commissions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('extra_commissions', [
            'user_id' => $this->salesPerson->id,
            'commission_type' => 'Bonus',
            'amount' => 1000,
            'status' => 'Pending',
        ]);
    });

    test('validation fails with invalid user', function () {
        $response = $this->actingAs($this->admin)
            ->post(route('extra-commissions.store'), [
                'user_id' => 9999,
                'commission_type' => 'Bonus',
                'amount' => 1000,
                'date_earned' => now()->format('Y-m-d'),
            ]);

        $response->assertSessionHasErrors(['user_id']);
    });
});

describe('Extra Commission Approve', function () {
    test('admin can approve pending commission', function () {
        $commission = ExtraCommission::factory()->pending()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('extra-commissions.approve', $commission));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('extra_commissions', [
            'id' => $commission->id,
            'status' => 'Approved',
            'approved_by' => $this->admin->id,
        ]);
    });
});

describe('Extra Commission Mark Paid', function () {
    test('admin can mark approved commission as paid', function () {
        $commission = ExtraCommission::factory()->approved()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('extra-commissions.mark-paid', $commission));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('extra_commissions', [
            'id' => $commission->id,
            'status' => 'Paid',
        ]);
    });
});

describe('Extra Commission Delete', function () {
    test('admin can delete extra commission', function () {
        $commission = ExtraCommission::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('extra-commissions.destroy', $commission));

        $response->assertRedirect();

        $this->assertDatabaseMissing('extra_commissions', ['id' => $commission->id]);
    });
});
