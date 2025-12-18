<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ClientDetail extends Model
{
    protected $fillable = [
        'conversion_id',
        'address',
        'billing_info',
        'support_contact_person',
        'whatsapp_group_created',
        'feedback',
        'remarketing_eligible',
    ];

    protected function casts(): array
    {
        return [
            'whatsapp_group_created' => 'boolean',
            'remarketing_eligible' => 'boolean',
        ];
    }

    public function conversion(): BelongsTo
    {
        return $this->belongsTo(Conversion::class);
    }

    /**
     * Get dynamic field values for this client
     */
    public function fieldValues(): MorphMany
    {
        return $this->morphMany(FieldValue::class, 'fieldable');
    }

    /**
     * Get a specific dynamic field value
     */
    public function getFieldValue(string $fieldName): ?string
    {
        return $this->fieldValues()
            ->whereHas('fieldDefinition', fn ($q) => $q->where('name', $fieldName))
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
}
