<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Console\Command;

class PruneRejectedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:prune-rejected-orders';
    protected $description = 'Rejected orders will be deleted from the database when one month passes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Reservation::where('status', OrderStatus::CANCELED->value)
            ->where('created_at', '<', now()->subMonth())
            ->delete();
    }
}
