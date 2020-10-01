<?php

namespace App\Console\Commands;

use App\charity_period;
use App\charity_periods_transaction;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class notifyPeriodLate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:periodLate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sms notice to periodic creation';

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

        $periodicTransaction = charity_periods_transaction::where('status','unpaid')
            ->where('payment_date','>=',date('Y-m-d',strtotime(date('Y-m-d')." -4 day")))
            ->where('payment_date','<',date('Y-m-d',strtotime(date('Y-m-d')." -3 day")))
            ->get();

        Log::info("routine late notify creation Run for ".count($periodicTransaction)." items");


        foreach ($periodicTransaction as $value){
            $phone = get_user($value['user_id'])['phone'];
            $user = User::find($value['user_id']);
            $name = get_name($value['user_id']);
            if (isset($user->people)){
                if ($user->people->name and $user->people->family){
                    $name = ($user->people->gender == 1 ? " آقای " :" خانم "). $name;
                }
            }
            $routine = charity_period::find($value['period_id']);

            $smsText = notification_messages('sms','reminderLate3',['name' => $name,'routine' => config('charity.routine_types.'.$routine['period'].'.title')]);

            $short_link= "";
            if ($value['slug']){
                $short_link.=' لینک پرداخت سریع: ';
                $short_link.=config('app.short_url')."/i/".$value['slug'];
            }
            if ($phone){
                sendSms($phone,$smsText['text'].$short_link);
            }else{
                Log::warning("routine late notify sms didnt sent to user for transaction with id of ".$value['id']);
            }
        }
    }
}
