<?php

namespace App\Services;

use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;

use App\Models\MemberSummary;

class MembershipReport
{

	public function __construct()
	{
  }

	public function Report()
	{
    $startDate = new \DateTime;
    $startDate->sub(new \DateInterval("P31D"));
    $startDate = $startDate->format(\DateTime::ATOM);

    $qbo = new QuickbooksService;        

    $customers = $qbo->getActiveMembers();
    $transactions = $qbo->getTransactions($startDate);

    $members = array();

    foreach($customers as $email => $customer)
    {
      $member = new MemberSummary;
      $member->email = $email;
      $member->name = $customer->GivenName . " " . $customer->FamilyName;
      $member->provider="Quickbooks";

      if(isset($transactions[$email]))
      {
        $member->paymentDate = $transactions[$email]->TxnDate;
        if ($transactions[$email]->CreditCardPayment != null)
        {
          if($transactions[$email]->CreditCardPayment->CreditChargeInfo != null)
          {
            $member->amount=$transactions[$email]->CreditCardPayment->CreditChargeInfo->Amount;
          }
          if($transactions[$email]->CreditCardPayment->CreditChargeResponse != null)
          {
            $member->status= $transactions[$email]->CreditCardPayment->CreditChargeResponse->Status;
          }
        } 
        else
        {
          $member->status = "???";
        }
      }
      else
      {
        $member->status = "Expired";
      }
      $members[] = $member;
    }


    //Get Paypal customers and payments
    $paypal = new PayPalService;
    $response = $paypal->searchTransactions($startDate, null);
    
    $i = 0;
    while(array_key_exists("L_TRANSACTIONID".$i, $response))
    {
      $member = new MemberSummary;
      $member->email = $this->get($response, "L_EMAIL".$i);
      $member->name = $this->get($response, "L_NAME".$i);
      $member->amount = $this->get($response, "L_AMT".$i);
      $member->paymentDate = $this->get($response, "L_TIMESTAMP".$i);
      $member->provider = "PayPal";
      $member->status = $this->get($response, "L_STATUS".$i);

      $members[] = $member;

      $i = $i + 1;
    }

    return $members;
  }

  private function get($arr, $key)
  {
    if (array_key_exists($key, $arr))
    {
      return $arr[$key];
    }
    else
    {
      return "";
    }

  }


}

