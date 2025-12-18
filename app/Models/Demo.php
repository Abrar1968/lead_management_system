<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Demo extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'created_by',
        'title',
        'description',
        'demo_date',
        'demo_time',
        'type',
        'status',
        'outcome_notes',
        'meeting_link',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'demo_date' => 'date',
            'demo_time' => 'datetime:H:i',
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

    /**
     * Get dynamic field values for this demo
     */
    public function fieldValues(): MorphMany
    {
        return $this->morphMany(FieldValue::class, 'fieldable');
    }

    /**
     * Get a specific dynamic field value by ID or name
     */
    public function getFieldValue(int|string $fieldIdOrName): ?string
    {
        if (is_int($fieldIdOrName)) {
            return $this->fieldValues()
                ->where('field_definition_id', $fieldIdOrName)
                ->first()?->value;
        }

        return $this->fieldValues()
            ->whereHas('fieldDefinition', fn ($q) => $q->where('name', $fieldIdOrName))
            ->first()?->value;
    }

    /**
     * Set a dynamic field value
     */
    public function setFieldValue(int $fieldDefinitionId, ?string $value): void
    {
        $this->fieldValues()->updateOrCreate(
            ['field_definition_id' => $fieldDefinitionId],
            ['value' => $value]
        );
    }

    /**
     * Scope for today's demos
     */
    public function scopeToday($query)
    {
        return $query->whereDate('demo_date', today());
    }

    /**
     * Scope for upcoming demos
     */
    public function scopeUpcoming($query)
    {
        return $query->where('demo_date', '>=', today())
            ->where('status', 'Scheduled')
            ->orderBy('demo_date')
            ->orderBy('demo_time');
    }
}
