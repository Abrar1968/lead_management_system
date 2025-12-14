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

    protected $fillable = [
        'lead_number',
        'source',
        'client_name',
        'phone_number',
        'email',
        'company_name',
        'service_interested',
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
        return $this->hasMany(LeadContact::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function conversion(): HasOne
    {
        return $this->hasOne(Conversion::class);
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
