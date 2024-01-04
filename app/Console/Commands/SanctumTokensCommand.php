<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumTokensCommand extends Command
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
        $tokenModel = User::find(1)->tokens()->getModel();
        DB::table($tokenModel->getTable())->where('expires_at', '<', now())->delete();    }
}
