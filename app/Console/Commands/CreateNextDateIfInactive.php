<?php

namespace App\Console\Commands;

use App\charity_period;
use Illuminate\Console\Command;

class CreateNextDateIfInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Create:NextDateIfInactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Next Date If Inactive';

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

        $charity = charity_period::where('status',"!=","active")->get();
        foreach ($charity as $item) {
            updateNextRoutine($item['id']);
        }
        return true;

    }
}
