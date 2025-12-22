<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class NoiseDatum extends Model
{
    use HasFactory;

    protected $fillable = ['leq', 'noise_meter_id', 'project_id', 'received_at', 'sound'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function noise_meter(): BelongsTo
    {
        return $this->belongsTo(NoiseMeter::class, 'noise_meter_id', 'id');
    }

    public function normalized_leq()
    {
        return pow(10, $this->leq / 10);
    }

    public static function leq5_data($query, $startHour, $endHour): Collection
    {
        return $query->whereBetween('received_at', [$startHour, $endHour])
            ->map(function ($datum) {
                return $datum->normalized_leq;
            });
    }
}
