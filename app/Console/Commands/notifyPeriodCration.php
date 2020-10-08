<?php

namespace App\Console\Commands;

use App\charity_period;
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

        $periodicTransaction = charity_periods_transaction::where('status', 'unpaid')
//            ->where('payment_date', '>=', date('Y-m-d', strtotime(date('Y-m-d H:i:s') . " -1 day")))
//            ->where('payment_date', '<=', date('Y-m-d'))
            ->where('payment_date', '=', date('Y-m-d'))
            ->get();
        Log::info("routine notify creation Run for ".count($periodicTransaction)." transactions");


        foreach ($periodicTransaction as $value) {
            $user = User::find($value['user_id']);

            if ($user and $user['phone']) {
                $user = User::find($value['user_id']);
                $name = get_name($value['user_id']);
                if (isset($user->people) and $user->people->name and $user->people->family) {
                    $name = ($user->people->gender == 1 ? " آقای " : " خانم ") . $name;
                }
                $routine = charity_period::find($value['period_id']);
                $smsText = notification_messages('sms', 'reminder', ['name' => $name,'routine' => config('charity.routine_types.'.$routine['period'].'.title')] );


                $short_link = "";
                if ($value['slug']) {
                    $short_link .= "\r\n";
                    $short_link .= ' لینک پرداخت سریع: ';
                    $short_link .= "\r\n";
                    $short_link .= config('app.short_url') . "/i/" . $value['slug'];
                }

                if ($user['phone']) {
                    sendSms($user['phone'], $smsText['text'] . $short_link);
                }
            }else{
                Log::warning("routine notify sms didnt sent to user for transaction with id of ".$value['id']);
            }
        }
    }
}
