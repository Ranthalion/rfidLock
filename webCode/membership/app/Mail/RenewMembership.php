<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Member;

class RenewMembership extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->member->level == "Premium")
        {
          return $this->view('emails.renewmembershipPremium')
              ->text('emails.renewmembershipPremium_plain');
        }
        else
        {
          return $this->view('emails.renewmembershipStandard')
              ->text('emails.renewmembershipStandard_plain');
        }

    }
}
