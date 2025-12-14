<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    /**
     * Meeting status options
     */
    public const MEETING_STATUSES = [
        'Positive' => ['label' => 'Positive', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'Negative' => ['label' => 'Negative', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'Confirmed' => ['label' => 'Confirmed', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'Pending' => ['label' => 'Pending', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
    ];

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
