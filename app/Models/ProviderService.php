<?php

namespace App\Models;

use App\Filters\ApplyFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(ServiceReservation::class, 'provider_service_id');
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount > 0)
            return $this->price - ($this->price * ($this->discount / 100));

        return null;
    }

    public function ScopeFilter(Builder $query,  ApplyFilter $filters)
    {
        return $filters->apply($query);
    }
}
