<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'default_commission_rate',
        'commission_type',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'default_commission_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function assignedLeads(): HasMany
    {
        return $this->leads();
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(Conversion::class, 'converted_by');
    }

    public function extraCommissions(): HasMany
    {
        return $this->hasMany(ExtraCommission::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'created_by');
    }

    public function leadContacts(): HasMany
    {
        return $this->hasMany(LeadContact::class, 'caller_id');
    }

    /**
     * Commission types assigned to this user
     */
    public function commissionTypes(): BelongsToMany
    {
        return $this->belongsToMany(CommissionType::class, 'user_commission_types')
            ->withPivot(['custom_rate', 'is_primary'])
            ->withTimestamps();
    }

    /**
     * Get the primary commission type for this user
     */
    public function primaryCommissionType(): ?CommissionType
    {
        return $this->commissionTypes()->wherePivot('is_primary', true)->first()
            ?? $this->commissionTypes()->first();
    }

    /**
     * Get the effective commission rate (from primary commission type or legacy field)
     */
    public function getEffectiveCommissionRate(): float
    {
        $primary = $this->primaryCommissionType();

        if ($primary) {
            $pivot = $this->commissionTypes()->where('commission_types.id', $primary->id)->first()?->pivot;

            return $pivot?->custom_rate ?? $primary->default_rate;
        }

        // Fallback to legacy commission_rate field
        return (float) $this->default_commission_rate;
    }

    /**
     * Get the effective commission type (fixed/percentage)
     */
    public function getEffectiveCommissionType(): string
    {
        $primary = $this->primaryCommissionType();

        return $primary?->calculation_type ?? $this->commission_type ?? 'fixed';
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSalesPerson(): bool
    {
        return $this->role === 'sales_person';
    }
}
