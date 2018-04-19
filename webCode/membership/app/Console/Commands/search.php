<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PaymentProviders\PayPalService;

class search extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:search {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $email = $this->argument('email');
        $paypal = new PayPalService;
        $member = $paypal->findMember($email);

        echo json_encode($member);
    }
}
