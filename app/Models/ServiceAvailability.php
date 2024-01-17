<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceAvailability extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'times',
    ];

    public function providerService(): BelongsTo
    {
        return $this->belongsTo(ProviderService::class);
    }
}
