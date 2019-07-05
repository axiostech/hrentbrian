<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class SyncTenancyStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:tenancystatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync tenancy status validity with date now';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('tenancies')
            ->whereDate('dateto', '<', Carbon::today()->toDateString())
            ->where('status', '1')
            ->update([
                'status' => 0
            ]);
    }
}
