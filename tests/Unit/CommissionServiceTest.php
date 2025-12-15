<?php

use App\Models\Conversion;
use App\Models\ExtraCommission;
use App\Models\Lead;
use App\Models\User;
use App\Services\CommissionService;

beforeEach(function () {
    $this->service = app(CommissionService::class);
});

describe('calculateCommission', function () {
    test('calculates fixed commission correctly', function () {
        $user = User::factory()->fixedCommission(500)->create();

        $commission = $this->service->calculateCommission($user, 50000);

        expect($commission)->toBe(500.0);
    });

    test('calculates percentage commission correctly', function () {
        $user = User::factory()->percentageCommission(10)->create();

        $commission = $this->service->calculateCommission($user, 50000);

        expect($commission)->toBe(5000.0);
    });

    test('returns zero for zero deal value with percentage', function () {
        $user = User::factory()->percentageCommission(10)->create();

        $commission = $this->service->calculateCommission($user, 0);

        expect($commission)->toBe(0.0);
    });

    test('returns fixed amount regardless of deal value', function () {
        $user = User::factory()->fixedCommission(500)->create();

        $commission1 = $this->service->calculateCommission($user, 10000);
        $commission2 = $this->service->calculateCommission($user, 100000);

        expect($commission1)->toBe(500.0);
        expect($commission2)->toBe(500.0);
    });
});

describe('getUserMonthlyCommission', function () {
    test('calculates monthly commission from conversions', function () {
        $user = User::factory()->create();
        $lead = Lead::factory()->converted()->create(['assigned_to' => $user->id]);

        Conversion::factory()->create([
            'lead_id' => $lead->id,
            'converted_by' => $user->id,
            'conversion_date' => now(),
            'commission_amount' => 500,
        ]);

        $monthlyCommission = $this->service->getUserMonthlyCommission(
            $user->id,
            now()->month,
            now()->year
        );

        expect((float) $monthlyCommission['standard'])->toBe(500.0);
        expect($monthlyCommission['total'])->toBe(500.0);
    });

    test('calculates for specific month', function () {
        $user = User::factory()->create();

        // Current month conversion
        $lead1 = Lead::factory()->converted()->create(['assigned_to' => $user->id]);
        Conversion::factory()->create([
            'lead_id' => $lead1->id,
            'converted_by' => $user->id,
            'conversion_date' => now(),
            'commission_amount' => 500,
        ]);

        // Last month conversion
        $lead2 = Lead::factory()->converted()->create(['assigned_to' => $user->id]);
        Conversion::factory()->create([
            'lead_id' => $lead2->id,
            'converted_by' => $user->id,
            'conversion_date' => now()->subMonth(),
            'commission_amount' => 1000,
        ]);

        $currentMonth = $this->service->getUserMonthlyCommission(
            $user->id,
            now()->month,
            now()->year
        );
        $lastMonth = $this->service->getUserMonthlyCommission(
            $user->id,
            now()->subMonth()->month,
            now()->subMonth()->year
        );

        expect($currentMonth['total'])->toBe(500.0);
        expect($lastMonth['total'])->toBe(1000.0);
    });
});

describe('getCommissionBreakdown', function () {
    test('returns breakdown with conversion and extra commissions', function () {
        $user = User::factory()->create();

        // Create conversion commission
        $lead = Lead::factory()->converted()->create(['assigned_to' => $user->id]);
        Conversion::factory()->create([
            'lead_id' => $lead->id,
            'converted_by' => $user->id,
            'conversion_date' => now(),
            'commission_amount' => 500,
        ]);

        // Create extra commission
        ExtraCommission::factory()->approved()->create([
            'user_id' => $user->id,
            'amount' => 1000,
            'date_earned' => now(),
        ]);

        $breakdown = $this->service->getCommissionBreakdown(
            $user->id,
            now()->month,
            now()->year
        );

        expect($breakdown)->toHaveKeys(['conversions', 'extra_commissions', 'conversion_commission', 'extra_commission', 'total']);
        expect($breakdown['conversion_commission'])->toBe(500.0);
        expect($breakdown['extra_commission'])->toBe(1000.0);
    });

    test('returns zero when no commissions', function () {
        $user = User::factory()->create();

        $breakdown = $this->service->getCommissionBreakdown(
            $user->id,
            now()->month,
            now()->year
        );

        expect($breakdown['conversion_commission'])->toBe(0.0);
        expect($breakdown['extra_commission'])->toBe(0.0);
    });

    test('accepts User model or integer ID', function () {
        $user = User::factory()->create();

        $breakdownById = $this->service->getCommissionBreakdown(
            $user->id,
            now()->month,
            now()->year
        );

        $breakdownByModel = $this->service->getCommissionBreakdown(
            $user,
            now()->format('Y-m')
        );

        expect($breakdownById)->toHaveKeys(['conversions', 'extra_commissions', 'conversion_commission', 'extra_commission', 'total']);
        expect($breakdownByModel)->toHaveKeys(['conversions', 'extra_commissions', 'conversion_commission', 'extra_commission', 'total']);
    });
});

describe('updateUserSettings', function () {
    test('updates commission type to fixed', function () {
        $user = User::factory()->percentageCommission(10)->create();

        $result = $this->service->updateUserSettings($user, 'fixed', 600);

        expect($result->commission_type)->toBe('fixed');
        expect((float) $result->default_commission_rate)->toBe(600.0);
    });

    test('updates commission type to percentage', function () {
        $user = User::factory()->fixedCommission(500)->create();

        $result = $this->service->updateUserSettings($user, 'percentage', 15);

        expect($result->commission_type)->toBe('percentage');
        expect((float) $result->default_commission_rate)->toBe(15.0);
    });
});
