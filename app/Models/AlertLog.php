<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_timestamp',
        'email_messageId',
        'email_debug',
        'sms_messageId',
        'sms_status_updated',
        'sms_status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'event_timestamp' => 'datetime',
        'sms_status_updated' => 'datetime',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
