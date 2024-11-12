<?php

namespace App\Models;

use App\Models\MeasurementPoint;
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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'measurement_point_id' => 'integer',
        'leq' => 'float',
        'received_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function measurementPoint(): BelongsTo
    {
        return $this->belongsTo(MeasurementPoint::class, 'id', 'measurement_point_id');
    }
}
