<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\RenewMembership;

class RenewalEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:renewalEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues renew membership emails';

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
        $renewals = DB::table('renewals')->get();
        foreach ($renewals as $member)
        {
            $this->info("Emailing. ".$member->email." ".$member->first." ".$member->level);

            \Mail::to($member)
                  ->send(new RenewMembership($member));
        }

    }
}
