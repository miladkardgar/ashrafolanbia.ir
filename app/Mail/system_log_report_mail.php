<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class system_log_report_mail extends Mailable
{
    use Queueable, SerializesModels;
//    public $name;
//    public $date;
//    public $amount;
//    public $ref_id;
    public $date;
    public $path;
    public $as;
    public $mime;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date,$path,$as,$mime)
    {
        $this->date = $date;
        $this->path = $path;
        $this->as = $as;
        $this->mime = $mime;
        $this->subject('system log file');

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.systemLogReport')
            ->attach($this->path, [
            'as' => $this->as,
            'mime' => $this->mime,
        ]);;
//        return view('global.callbackmain', compact('messages'));

    }
}
