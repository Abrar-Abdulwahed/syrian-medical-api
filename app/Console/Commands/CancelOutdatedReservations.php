<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Console\Command;

class CancelOutdatedReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-outdated';
    protected $description = 'Cancel pending outdated reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Reservation::where('status', 'pending')
            ->whereDate('date', '<', Carbon::now())
            ->update(['status' => OrderStatus::CANCELED->value]);
    }
}
