<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_date',
        'appointment_time',
    ];

    public static function isAvailable($date, $time)
    {
        return !ServiceReservation::whereDate('appointment_date', Carbon::parse($date))->where('appointment_time', $time)->exists();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ProviderService::class, 'provider_service_id');
    }

    public function morphReservation(): MorphOne
    {
        return $this->morphOne(Reservation::class, 'reservationable');
    }

    public function getProviderAttribute()
    {
        return $this->service->provider;
    }
}
