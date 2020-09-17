<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Revoked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:revoked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revoke membership for members without a payment in the previous 60 days.';

    protected $paymentImporter
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
        //TODO: [ML] Revoke membership and send email
        $this->paymentImporter->revokeMembers();
    }
}
