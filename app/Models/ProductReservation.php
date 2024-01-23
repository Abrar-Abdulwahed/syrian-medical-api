<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function morphReservation(): MorphOne
    {
        return $this->morphOne(Reservation::class, 'reservationable');
    }

    public function getProviderAttribute()
    {
        return $this->product->provider;
    }
}
