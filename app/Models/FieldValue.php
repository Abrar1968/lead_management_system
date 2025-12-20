<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FieldValue extends Model
{
    protected $fillable = [
        'field_definition_id',
        'fieldable_id',
        'fieldable_type',
        'value',
    ];

    public function fieldDefinition(): BelongsTo
    {
        return $this->belongsTo(FieldDefinition::class);
    }

    public function fieldable(): MorphTo
    {
        return $this->morphTo();
    }
}
