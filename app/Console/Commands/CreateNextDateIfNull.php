<?php

namespace App\Console\Commands;

use App\charity_period;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateNextDateIfNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Create:NextDateIfNull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Next Date If Null';

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

        $charity = charity_period::whereNull('next_date')->get();
        Log::info("Charity routine null updater Run for ".count($charity)." item");

        foreach ($charity as $item) {
            updateNextRoutine($item['id']);
        }
        return true;

    }
}
