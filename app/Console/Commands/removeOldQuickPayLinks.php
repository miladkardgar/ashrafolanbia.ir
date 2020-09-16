<?php

namespace App\Console\Commands;

use App\charity_periods_transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class removeOldQuickPayLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Remove:oldQuickPayLinks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Quick pay links older than 30 days';

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
        Log::info("Remove quick pay link Run at " . date("Y-m-d H:i:s"));

        charity_periods_transaction::where('payment_date','<=',date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s')."-30 days")))
        ->update(['slug'=>null]);
    }
}
