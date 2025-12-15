<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'lead_id',
        'follow_up_id',
        'meeting_date',
        'meeting_time',
        'meeting_type',
        'meeting_status',
        'outcome',
        'price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
            'meeting_time' => 'datetime:H:i',
            'price' => 'decimal:2',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function followUp(): BelongsTo
    {
        return $this->belongsTo(FollowUp::class);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('meeting_date', $date);
    }
}
