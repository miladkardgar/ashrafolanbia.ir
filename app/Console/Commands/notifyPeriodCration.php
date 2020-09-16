<?php

namespace App\Console\Commands;

use App\charity_periods_transaction;
use App\notification_template;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class notifyPeriodCration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:periodCreation';

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
        Log::info("routine notify creation Run At" . date("Y-m-d H:i:s"));

        $periodicTransaction = charity_periods_transaction::where('status','unpaid')
            ->where('payment_date','>=',date('Y-m-d',strtotime(date('Y-m-d')." -1 day")))
            ->where('payment_date','<',date('Y-m-d'))
            ->get();


        foreach ($periodicTransaction as $value){
            $phone = get_user($value['user_id'])['phone'];
            $user = User::find($value['user_id']);
            $name = get_name($value['user_id']);
            if ($user->people){
                if ($user->people->name and $user->people->family){
                    $name = ($user->people->gender == 1 ? " آقای " :" خانم "). $name;
                }
            }
            $smsText = notification_messages('sms','reminder',['name' => $name]);


            $short_link= "";
            if ($value['slug']){
                $short_link.="\r\n";
                $short_link.=' لینک پرداخت سریع: ';
                $short_link.="\r\n";
                $short_link.=config('app.short_url')."/i/".$value['slug'];
            }

            if ($phone){
                sendSms($phone,$smsText['text'] . $short_link);
            }
        }
    }
}
