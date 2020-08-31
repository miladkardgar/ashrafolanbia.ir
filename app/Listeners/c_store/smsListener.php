<?php

namespace App\Listeners\c_store;

use App\Events\c_storePaymentAlert;
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
     * @param  c_storePaymentAlert  $event
     * @return void
     */
    public function handle(c_storePaymentAlert $event)
    {
        if (isset($event->smsData['phone'])){
            $template = notification_template::where('key','c_store_alert')->first();

            $message = $template->text;
            $variables = explode(',',$template['variables']);
            foreach ($variables as $variable){
                $newVariable = (isset($event->smsData[$variable])? $event->smsData[$variable]:" -- ");
                $message = str_replace("{".$variable."}",$newVariable,$message);
            }

            sendSms($event->smsData['phone'],$message);
        }

    }
}
