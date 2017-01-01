<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentImporter;

class FailedQuickbooksPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:failedQuickbooksPayments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends failed payment emails';

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
        $this->paymentImporter->getFailedPayments();
    }
}
