<?php

namespace App\Listeners\register;

use App\Events\userRegisterEvent;
use App\notification_template;
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
     * @param  userRegisterEvent  $event
     * @return void
     */
    public function handle(userRegisterEvent $event)
    {

        if ($event->user->phone){
            $template = notification_template::where('key','new_register')->first();
            $message = str_replace("{code}",$event->user->code_phone,$template->text);
            $message = str_replace("{name}",get_name($event->user->id),$message);
            sendSms($event->user->phone,$message);
        }
    }
}
