<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderService extends Pivot
{
    use HasFactory;

    // Relationship

    // dates - working days
    public function availabilities()
    {
        return $this->hasMany(ServiceAvailability::class, 'provider_service_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reservations(): hasMany
    {
        return $this->hasMany(ServiceReservation::class, 'provider_service_id');
    }
}
