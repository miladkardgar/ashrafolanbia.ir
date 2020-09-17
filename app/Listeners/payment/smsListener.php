<?php

namespace App\Listeners\payment;

use App\Events\payToCharityMoney;
use App\notification_template;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

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
     * @param payToCharityMoney $event
     * @return void
     */
    public function handle(payToCharityMoney $event)
    {
        try {
            if (isset($event->smsData['phone'])) {
                $template = notification_template::where('key', 'payConfirm')->first();

                $message = $template->text;
                $variables = explode(',', $template['variables']);
                foreach ($variables as $variable) {
                    $newVariable = (isset($event->smsData[$variable]) ? $event->smsData[$variable] : " -- ");
                    $message = str_replace("{" . $variable . "}", $newVariable, $message);
                }

                sendSms($event->smsData['phone'], $message);
            } else {
                Log::warning('cant sent sms after payment phone is empty');
            }
        } catch (\Throwable $e) {
            Log::error($e);
        }
    }
}
