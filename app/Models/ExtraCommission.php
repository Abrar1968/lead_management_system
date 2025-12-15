<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commission_type',
        'amount',
        'description',
        'date_earned',
        'related_conversion_id',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'date_earned' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedConversion(): BelongsTo
    {
        return $this->belongsTo(Conversion::class, 'related_conversion_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }
}
