<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPerformance extends Model
{
    protected $table = 'sales_performance_cache';

    protected $fillable = [
        'user_id',
        'total_leads',
        'total_conversions',
        'conversion_rate',
        'total_revenue',
        'avg_deal_value',
        'total_calls',
        'total_follow_ups',
        'total_meetings',
        'response_rate',
        'follow_up_rate',
        'active_leads',
        'pending_follow_ups',
        'avg_conversion_days',
        'avg_first_contact_hours',
        'performance_score',
        'period_type',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'conversion_rate' => 'decimal:2',
            'total_revenue' => 'decimal:2',
            'avg_deal_value' => 'decimal:2',
            'response_rate' => 'decimal:2',
            'follow_up_rate' => 'decimal:2',
            'avg_conversion_days' => 'decimal:2',
            'avg_first_contact_hours' => 'decimal:2',
            'performance_score' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the latest performance for a user.
     */
    public static function getLatestForUser(int $userId, string $periodType = 'monthly'): ?self
    {
        return static::where('user_id', $userId)
            ->where('period_type', $periodType)
            ->latest('period_end')
            ->first();
    }

    /**
     * Scope for current period.
     */
    public function scopeCurrentPeriod($query, string $periodType = 'monthly')
    {
        $now = now();

        return match ($periodType) {
            'daily' => $query->whereDate('period_start', $now->toDateString()),
            'weekly' => $query->where('period_start', '<=', $now)->where('period_end', '>=', $now),
            'monthly' => $query->whereMonth('period_start', $now->month)->whereYear('period_start', $now->year),
            default => $query,
        };
    }
}
