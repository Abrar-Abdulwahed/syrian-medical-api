<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    function getStatusLabel($status, $locale)
    {
        // match expression => PHP 8.x
        $status_label = match ($status) {
            OrderStatus::PENDING->value   => getLocalizedEnumValue(OrderStatus::PENDING, $locale),
            OrderStatus::ACCEPTED->value  => getLocalizedEnumValue(OrderStatus::ACCEPTED, $locale),
            OrderStatus::PAID->value      => getLocalizedEnumValue(OrderStatus::PAID, $locale),
            OrderStatus::DELIVERED->value => getLocalizedEnumValue(OrderStatus::DELIVERED, $locale),
            OrderStatus::CANCELED->value  => getLocalizedEnumValue(OrderStatus::CANCELED, $locale),
        };
        return $status_label;
    }

    public function reservationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function rejectionReason(): HasOne
    {
        return $this->hasOne(RejectionReason::class);
    }
}
