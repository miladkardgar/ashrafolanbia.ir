<?php

namespace App\Console\Commands;

use App\charity_period;
use App\charity_periods_transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateCharityPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Create:charityPeriod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create periodic charity payment';

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
        Log::notice("Charity period maker Run At" . date("Y-m-d H:i:s"));

        $charity = charity_period::where('status', 'active')->where("next_date", "<=", date("Y-m-d"))->get();
        foreach ($charity as $item) {

            $exists = charity_periods_transaction::where('period_id', $item['id'])
                ->where('payment_date', $item['next_date'])->exists();
            $description= '';
            if (array_key_exists($charity->period,config('charity.routine_types'))){
                $description =  config('charity.routine_types')[$charity->period]['title'];
            };

            if (!$exists) {
                charity_periods_transaction::create(
                    [
                        'user_id' => $item['user_id'],
                        'period_id' => $item['id'],
                        'payment_date' => $item['next_date'],
                        'amount' => $item['amount'],
                        'description' => $item['id'] . " " .($description ? $description :"پرداخت دوره ای "),
                        'status' => "unpaid",
                    ]
                );
                updateNextRoutine($item['id']);
            }
        }
        return true;
    }
}
