<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_date',
        'appointment_time',
        'location',
        'payment_method'
    ];

    protected $casts = [
        'location'        => 'json',
        'payment_method'  => 'json',
    ];

    public function reservationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public static function isAvailable($date, $time)
    {
        return !Reservation::where('appointment_date', Carbon::parse($date))->where('appointment_time', $time)->exists();
    }
}
