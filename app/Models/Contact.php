<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'contact_person_name',
        'designation',
        'phone_number',
        'fax_number',
        'email',
        'contact_person_code',
        'office_tel',
        'alert_status',
        'days_of_alert',
        'alert_start_hour',
        'alert_end_hour',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'project_id' => 'integer',
        'alert_status' => 'integer',
        'alert_start_hour' => 'integer',
        'alert_end_hour' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function isAlertActive(): bool
    {
        return $this->alert_status === 1;
    }
}
