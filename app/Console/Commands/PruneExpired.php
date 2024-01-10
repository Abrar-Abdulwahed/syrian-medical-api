<?php

namespace App\Console\Commands;

use Laravel\Sanctum\Sanctum;
use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class PruneExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum:prune-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired sanctum tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = Sanctum::$personalAccessTokenModel;
        $model::whereDate('expires_at', '<', now())->delete();
    }
}
