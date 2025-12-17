<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Centralized Lead Status definitions.
     * Single source of truth for all status-related logic.
     */
    public const STATUSES = [
        'New' => ['label' => 'New', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
        'Contacted' => ['label' => 'Contacted', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'Qualified' => ['label' => 'Qualified', 'color' => 'indigo', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-800'],
        'Negotiation' => ['label' => 'Negotiation', 'color' => 'orange', 'bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
        'Converted' => ['label' => 'Converted', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'Lost' => ['label' => 'Lost', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
    ];

    /**
     * Get valid status values for validation.
     */
    public static function getStatusValues(): array
    {
        return array_keys(self::STATUSES);
    }

    /**
     * Get status validation rule string.
     */
    public static function getStatusValidationRule(): string
    {
        return 'in:'.implode(',', self::getStatusValues());
    }

    protected $fillable = [
        'lead_number',
        'source',
        'client_name',
        'phone_number',
        'email',
        'company_name',
        'service_interested',
        'service_id',
        'lead_date',
        'lead_time',
        'is_repeat_lead',
        'previous_lead_ids',
        'priority',
        'status',
        'assigned_to',
    ];

    protected function casts(): array
    {
        return [
            'lead_date' => 'date',
            'lead_time' => 'datetime:H:i',
            'is_repeat_lead' => 'boolean',
            'previous_lead_ids' => 'array',
        ];
    }

    // Accessor for customer_name to map to client_name
    protected function customerName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->client_name,
            set: fn ($value) => ['client_name' => $value],
        );
    }

    // Relationships
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(LeadContact::class)->latest('call_date')->latest('call_time');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class)->latest('follow_up_date')->latest('follow_up_time');
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class)->latest('meeting_date')->latest('meeting_time');
    }

    public function conversion(): HasOne
    {
        return $this->hasOne(Conversion::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Scopes
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('lead_date', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('lead_date', $month)->whereYear('lead_date', $year);
    }
}
