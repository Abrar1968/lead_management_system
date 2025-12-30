<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class CommissionType extends Model
{
    /** @use HasFactory<\Database\Factories\CommissionTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'calculation_type',
        'default_rate',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'default_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Users assigned to this commission type
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_commission_types')
            ->withPivot(['custom_rate', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the effective rate for a user (custom rate or default)
     */
    public function getEffectiveRateForUser(User $user): float
    {
        $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;

        return $pivot?->custom_rate ?? $this->default_rate;
    }

    /**
     * Calculate commission for a given deal value
     */
    public function calculateCommission(float $dealValue, ?float $customRate = null): float
    {
        $rate = $customRate ?? $this->default_rate;

        if ($this->calculation_type === 'fixed') {
            return round($rate, 2);
        }

        // Percentage calculation
        return round(($dealValue * $rate) / 100, 2);
    }

    /**
     * Scope for active commission types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for default commission types
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
