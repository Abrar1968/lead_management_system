<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUpRuleCondition extends Model
{
    protected $fillable = [
        'rule_id',
        'field',
        'operator',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * Available operators
     */
    public const OPERATORS = [
        'equals' => 'Equals',
        'not_equals' => 'Not Equals',
        'greater_than' => 'Greater Than',
        'less_than' => 'Less Than',
        'between' => 'Between',
        'in' => 'In List',
        'not_in' => 'Not In List',
        'is_null' => 'Is Empty',
        'is_not_null' => 'Has Value',
    ];

    /**
     * Available fields for rule conditions
     */
    public const AVAILABLE_FIELDS = [
        // Lead fields
        'lead.status' => [
            'label' => 'Lead Status',
            'type' => 'enum',
            'values' => ['New', 'Contacted', 'Qualified', 'Negotiation', 'Converted', 'Lost'],
        ],
        'lead.priority' => [
            'label' => 'Lead Priority',
            'type' => 'enum',
            'values' => ['High', 'Medium', 'Low'],
        ],
        'lead.source' => [
            'label' => 'Lead Source',
            'type' => 'enum',
            'values' => ['WhatsApp', 'Messenger', 'Website'],
        ],
        'lead.is_repeat_lead' => [
            'label' => 'Is Repeat Lead',
            'type' => 'boolean',
            'values' => [true, false],
        ],
        'lead.days_since_lead' => [
            'label' => 'Days Since Lead Created',
            'type' => 'number',
            'values' => null,
        ],

        // Contact fields
        'contact.response_status' => [
            'label' => 'Last Response Status',
            'type' => 'enum',
            'values' => ['Yes', 'No', 'No Res.', '50%', 'Call Later', 'Phone off', 'Interested', 'Demo Delivered', '80%'],
        ],
        'contact.total_calls' => [
            'label' => 'Total Calls Made',
            'type' => 'number',
            'values' => null,
        ],
        'contact.days_since_last_call' => [
            'label' => 'Days Since Last Call',
            'type' => 'number',
            'values' => null,
        ],

        // Follow-up fields
        'followup.interest' => [
            'label' => 'Follow-up Interest',
            'type' => 'enum',
            'values' => ['Yes', 'No', 'No Response', 'Call Later', '50%'],
        ],
        'followup.pending_count' => [
            'label' => 'Pending Follow-ups Count',
            'type' => 'number',
            'values' => null,
        ],
        'followup.days_since_last' => [
            'label' => 'Days Since Last Follow-up',
            'type' => 'number',
            'values' => null,
        ],

        // Meeting fields
        'meeting.has_meeting' => [
            'label' => 'Has Any Meeting',
            'type' => 'boolean',
            'values' => [true, false],
        ],
        'meeting.last_outcome' => [
            'label' => 'Last Meeting Outcome',
            'type' => 'enum',
            'values' => ['Pending', 'Completed', 'Cancelled', 'Rescheduled'],
        ],
    ];

    /**
     * Parent rule
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(FollowUpRule::class, 'rule_id');
    }

    /**
     * Get field label
     */
    public function getFieldLabelAttribute(): string
    {
        return self::AVAILABLE_FIELDS[$this->field]['label'] ?? $this->field;
    }

    /**
     * Get operator label
     */
    public function getOperatorLabelAttribute(): string
    {
        return self::OPERATORS[$this->operator] ?? $this->operator;
    }
}
