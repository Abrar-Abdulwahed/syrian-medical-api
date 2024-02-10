<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Console\Command;
use App\Models\ServiceReservation;

class CancelOutdatedReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-outdated';
    protected $description = 'Cancel pending outdated appointments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Reservation::where('status', OrderStatus::PENDING->value)
            ->where('reservationable_type', ServiceReservation::class)
            ->whereDoesntHave('reservationable.service_availabilities', function ($query) {
                $query->where('date', '>=', now());
            })
            ->update(['status' => OrderStatus::CANCELED->value]);
    }
}
