<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class sendSystemLogFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:systemLogFile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send system log file by email';

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
        try{
            Log::info("log file sent to registered emails");

            $date = date("Y-m-d",strtotime(date("Y-m-d")." -1 day"));
            $path = storage_path('/logs/laravel-'.$date.'.log');
            if(file_exists($path)) {
                // log files exist
                $mails = config('logging.mails');
                Mail::to($mails)->send(new \App\Mail\system_log_report_mail($date,$path,$date."-report.log",".log"));
            }
        }catch (\Throwable $e){
            Log::info("failed to sent log file to email");

        }
    }
}
