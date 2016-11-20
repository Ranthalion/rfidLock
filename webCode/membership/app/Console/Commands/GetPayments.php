<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentImporter;

class GetPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:getPayments {days=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import payment data from Quickbooks and Paypal';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $paymentImporter;

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
        $days = $this->argument('days');
        $this->paymentImporter->import($days);
    }
}
