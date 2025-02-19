<?php

namespace App\Models;

use App\Models\MeasurementPoint;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoiseData extends Model
{
    use HasFactory;

    protected $table = 'noise_data';

    protected $fillable = [
        'measurement_point_id',
        'leq',
        'received_at',
        'noise_meter_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'measurement_point_id' => 'integer',
        'leq' => 'float',
        'received_at' => 'datetime',
        'noise_meter_id' => 'integer'
    ];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'id', 'measurement_point_id');
    }

    public function noiseMeter(): BelongsTo
    {
        return $this->belongsTo(NoiseMeter::class, "noise_meter_id", "id");
    }
}
