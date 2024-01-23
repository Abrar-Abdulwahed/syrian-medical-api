<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProviderService extends Pivot
{
    use HasFactory;

    // Relationship

    // dates - working days
    public function availabilities()
    {
        return $this->hasMany(ServiceAvailability::class, 'provider_service_id');
    }

    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'reservationable');
    }
}
