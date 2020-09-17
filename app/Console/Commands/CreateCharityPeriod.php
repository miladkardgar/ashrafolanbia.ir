<?php

namespace App\Console\Commands;

use App\charity_period;
use App\charity_periods_transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $charity = charity_period::where('status', 'active')->where("next_date", "<=", date("Y-m-d 23:59:59"))->limit(300)->get();
        Log::info("Charity routine maker Run For ".count($charity)." item");

        foreach ($charity as $item) {

            $exists = charity_periods_transaction::where('period_id', $item['id'])
                ->where('payment_date', $item['next_date'])->exists();
            $description= '';
            if (array_key_exists($item->period,config('charity.routine_types'))){
                $description =  config('charity.routine_types')[$item->period]['title'];
            };

            if (!$exists) {
                $random = Str::random(6);
                while (charity_periods_transaction::where('slug',$random)->exists()){
                    $random = Str::random(7);
                }
                charity_periods_transaction::create(
                    [
                        'user_id' => $item['user_id'],
                        'period_id' => $item['id'],
                        'payment_date' => $item['next_date'],
                        'amount' => $item['amount'],
                        'description' => $item['id'] . " " .($description ? $description :"پرداخت دوره ای "),
                        'status' => "unpaid",
                        'slug' => $random,
                    ]
                );
                updateNextRoutine($item['id']);
            }
        }
        return true;
    }
}
