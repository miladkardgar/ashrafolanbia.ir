<?php

namespace App\Listeners;

use App\Mail\userRegisterMail;
use Illuminate\Support\Facades\Mail;

class mailListener
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        try{
            if ($event->user->email){
                Mail::to($event->user->email)->send(new userRegisterMail($event->user));
            }
        }catch (\Throwable $e){
            return false;
        }

    }
}
