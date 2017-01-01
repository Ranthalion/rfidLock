<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class failedPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:failedPayments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send failed payment emails';

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
        //TODO: [ML] Find failed payments and send failure emails
        //Find all active members that have not had a payment within the last month and haven't been notified in the last month either
    }
}
