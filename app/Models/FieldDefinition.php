<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldDefinition extends Model
{
    protected $fillable = [
        'model_type',
        'name',
        'label',
        'type',
        'required',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(FieldValue::class);
    }

    /**
     * Scope to filter by model type
     */
    public function scopeForModel($query, string $modelType)
    {
        return $query->where('model_type', $modelType)->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope for client fields
     */
    public function scopeForClient($query)
    {
        return $query->forModel('client');
    }

    /**
     * Scope for demo fields
     */
    public function scopeForDemo($query)
    {
        return $query->forModel('demo');
    }
}
