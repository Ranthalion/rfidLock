<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentImporter;

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

    protected $paymentImporter;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PaymentImporter $importer)
    {
        parent::__construct();
        $this->paymentImporter = $importer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //TODO: [ML] Send pending revokation emails
        $this->paymentImporter->pendingRevokation();
    }
}
