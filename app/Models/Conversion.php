<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'converted_by',
        'conversion_date',
        'deal_value',
        'commission_rate_used',
        'commission_type_used',
        'commission_amount',
        'package_plan',
        'advance_paid',
        'payment_method',
        'signing_date',
        'signing_time',
        'delivery_deadline',
        'expected_delivery_date',
        'actual_delivery_date',
        'project_status',
        'commission_paid',
    ];

    protected function casts(): array
    {
        return [
            'conversion_date' => 'date',
            'signing_date' => 'date',
            'signing_time' => 'datetime:H:i',
            'delivery_deadline' => 'date',
            'expected_delivery_date' => 'date',
            'actual_delivery_date' => 'date',
            'deal_value' => 'decimal:2',
            'commission_rate_used' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'advance_paid' => 'boolean',
            'commission_paid' => 'boolean',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function convertedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function clientDetail(): HasOne
    {
        return $this->hasOne(ClientDetail::class);
    }

    public function extraCommissions(): HasMany
    {
        return $this->hasMany(ExtraCommission::class, 'related_conversion_id');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('conversion_date', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('conversion_date', $month)->whereYear('conversion_date', $year);
    }
}
