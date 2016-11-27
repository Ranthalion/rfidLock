<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PendingRevokation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:pendingRevokation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends emails for members pending revokation in the next 15 days';

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
        //TODO: [ML] Send pending revokation emails
    }
}
