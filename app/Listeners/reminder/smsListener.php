<?php

namespace App\Listeners\reminder;

use App\Events\paymentReminder;
use App\notification_template;
use App\person;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class smsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  paymentReminder  $event
     * @return void
     */
    public function handle(paymentReminder $event)
    {
        if ($event->user->phone){
            $template = notification_template::where('key','reminder')->first();
            $people = person::where('user_id')->get();
            $name = "";
            if ($people){
            $name = ($people->gender == 1 ? " آقای " :" خانم "). $people->name." ".$people->family;
            }

            $message = str_replace("{name}",$name,$template->text);
            sendSms($event->user->phone,$message);
        }
    }
}
