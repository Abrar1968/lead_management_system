<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'daily_call_made',
        'call_date',
        'call_time',
        'caller_id',
        'response_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'call_date' => 'date',
            'call_time' => 'datetime:H:i',
            'daily_call_made' => 'boolean',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('call_date', $date);
    }
}
