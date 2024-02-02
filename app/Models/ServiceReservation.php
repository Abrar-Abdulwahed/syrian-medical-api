<?php

namespace App\Models;

use App\Enums\OrderStatus;
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
    public $timestamps = false;

    public static function isAvailable($date, $time)
    {
        return !ServiceReservation::whereDate('appointment_date', Carbon::parse($date))
            ->whereTime('appointment_time', $time)
            ->whereRelation('morphReservation', 'status', OrderStatus::PENDING->value)
            ->exists();
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
