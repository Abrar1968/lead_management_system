<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LeadAssignmentSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("lead_assignment_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting?->value ?? $default;
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, ?string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
            ]
        );

        Cache::forget("lead_assignment_setting_{$key}");
    }

    /**
     * Get scoring weights.
     */
    public static function getScoringWeights(): array
    {
        return static::get('scoring_weights', [
            'conversion_rate' => 30,
            'response_rate' => 20,
            'follow_up_rate' => 15,
            'avg_deal_value' => 15,
            'workload_balance' => 20,
        ]);
    }

    /**
     * Get max active leads per sales person.
     */
    public static function getMaxActiveLeads(): int
    {
        return static::get('max_active_leads', 20);
    }

    /**
     * Get assignment mode.
     */
    public static function getAssignmentMode(): string
    {
        return static::get('assignment_mode', 'balanced');
    }

    /**
     * Check if auto-assign is enabled.
     */
    public static function isAutoAssignEnabled(): bool
    {
        return static::get('auto_assign_enabled', false);
    }

    /**
     * Get calculation period.
     */
    public static function getCalculationPeriod(): string
    {
        return static::get('calculation_period', 'monthly');
    }
}
