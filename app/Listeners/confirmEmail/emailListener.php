<?php

namespace App\Listeners\confirmEmail;

use App\Events\confirmEmail;
use App\Mail\payment_confirmation;
use App\Mail\userRegisterMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class emailListener
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
     * @param  confirmEmail  $event
     * @return void
     */
    public function handle(confirmEmail $event)
    {
        try{
            Mail::to($event->mailData['address'])->send(new \App\Mail\confirmEmail($event->mailData['code']));
        }catch (\Throwable $e){

        }

    }
}
