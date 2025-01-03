<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Concentrator extends Model
{
    use HasFactory;
    protected $table = "concentrators";

    protected $fillable = [
        'device_id',
        'concentrator_label',
        'concentrator_csq',
        'concentrator_hp',
        'battery_voltage',
        'last_communication_packet_sent',
        'last_assigned_ip_address',
        'remarks',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'device_id' => 'string',
        'concentrator_label' => 'string',
        'concentrator_csq' => 'integer',
        'concentrator_hp' => 'string',
        'battery_voltage' => 'float',
        'last_communication_packet_sent' => 'datetime',
        'last_assigned_ip_address' => 'string',
        'hardware_revision_number' => 'integer',
        'remarks' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'id', 'concentrator_id');
    }

    public function has_running_project()
    {
        $project = $this->measurementPoint->project;
        return $project !== null && $project->isRunning();
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}