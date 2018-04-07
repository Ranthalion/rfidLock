<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentImporter;

class ImportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:importPayments {startDate} {endDate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import payment data from Paypal';

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
        $startDate = $this->argument('startDate');
        $endDate = $this->argument('startDate');
        $this->info("Querying payments.");
        $this->paymentImporter->importByDateRange($startDate, $endDate);
        $this->info("Payments imported.");
    }
}
