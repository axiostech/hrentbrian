<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Arc;
use App\Jobs\CheckUpdateEnrpStatusJob;

class CheckCurlecEnrpStatusUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:curlec-enrp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Curlec Enrp Status Update';

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
        $arcs = Arc::whereNull('enrp_status')
                ->where('status', 3)
                ->get();
        foreach($arcs as $arc) {
            CheckUpdateEnrpStatusJob::dispatch($arc->id);
        }
    }
}
