<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderService extends Pivot
{
    use HasFactory;

    // Relationship
    public function availabilities()
    {
        return $this->hasMany(ServiceAvailability::class);
    }
}
