<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    use HasFactory;

    /**
     * Interest status options
     */
    public const INTEREST_STATUSES = [
        'Yes' => ['label' => 'Yes', 'color' => 'green', 'bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'No' => ['label' => 'No', 'color' => 'red', 'bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'No Response' => ['label' => 'No Response', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
        '50%' => ['label' => '50%', 'color' => 'yellow', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
        'Phone Off' => ['label' => 'Phone Off', 'color' => 'gray', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
        'Call Later' => ['label' => 'Call Later', 'color' => 'blue', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
    ];

    protected $fillable = [
        'lead_id',
        'follow_up_date',
        'follow_up_time',
        'notes',
        'status',
        'interest',
        'price',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'follow_up_time' => 'datetime:H:i',
            'price' => 'decimal:2',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('follow_up_date', today());
    }
}
