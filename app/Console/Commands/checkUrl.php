<?php

namespace App\Console\Commands;

use App\Forbidden;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class checkUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkUrl {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a URL is valid';

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
        //Get all the forbidden string
        $forbiddeen = Forbidden::all();

        //Loop through the forbidden string
        foreach ($forbiddeen as $bad) {
            $place = strpos($this->argument('url'), $bad->contains);
            if (!empty($place)) {
                 $this->error("Contains forbidden string: "
                    . $bad->contains);
            }
        }
        $this->info("All good!");
    }
}
