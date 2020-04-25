<?php

namespace App\Listeners\confirmPhone;

use App\Events\confirmPhone;
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
     * @param  confirmPhone  $event
     * @return void
     */
    public function handle(confirmPhone $event)
    {
        if (isset($event->smsData['phone'])){
            $message = "کد تایید:" . $event->smsData['code'];
            sendSms($event->smsData['phone'],$message);
        }
    }
}
