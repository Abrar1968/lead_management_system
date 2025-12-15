<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
