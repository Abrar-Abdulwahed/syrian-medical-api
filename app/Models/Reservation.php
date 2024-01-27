<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'payment_method'
    ];

    protected $casts = [
        'location'        => 'json',
        'payment_method'  => 'json',
    ];

    function getStatusLabel($status)
    {
        $labels = [
            OrderStatus::PENDING->value => 'pending',
            OrderStatus::COMPLETED->value => 'accepted',
            OrderStatus::CANCELED->value => 'refused',
        ];

        return $labels[$status] ?? '';
    }

    public function reservationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function rejectionReason(): HasOne
    {
        return $this->hasOne(RejectionReason::class);
    }
}
