<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;

class OrderPolicy
{
    public function manageReservations(User $user, ?Reservation $reservation): bool
    {
        return $user->id === $reservation->reservationable?->provider->id;
    }
}
