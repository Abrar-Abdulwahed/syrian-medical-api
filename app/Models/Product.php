<?php

namespace App\Models;

use App\Models\ProviderProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public function providers(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class);
    }
}
