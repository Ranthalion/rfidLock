<?php

namespace App\Services;

use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;

use App\Models\MemberSummary;
use App\Models\Customer;
use App\Models\Payment;

class PaymentImporter
{

	public function __construct()
	{

  }

  public function import($days)
  {
    $startDate = new \DateTime;
    $startDate->sub(new \DateInterval("P".$days."D"));
    $startDate = $startDate->format(\DateTime::ATOM);

    $customer_payments = $this->getCustomerPayments($startDate);

    foreach($customer_payments as $payment)
    {
      $customer = null;
      
      if ($payment->paymentDate != null && $payment->amount != null)
      {
        if (filter_var($payment->email, FILTER_VALIDATE_EMAIL))
        {

          $payment->paymentDate = new \DateTimeImmutable($payment->paymentDate);
          $nextPayment = $payment->paymentDate->add(new \DateInterval("P1M"));

          if ($payment->provider == 'PayPal')
            $payment->provider = 2;
          else
            $payment->provider = 1;

          $customer = Customer::updateOrCreate(
            [ 'email' => $payment->email,
              'name' => $payment->name
            ], 
            [ 'last_payment_date' => $payment->paymentDate, 
              'last_payment_amount' => $payment->amount,
              'payment_provider_id' => $payment->provider,
              'next_payment_date' => $nextPayment,
              'last_payment_status' => $payment->status
            ]);

          if ($customer != null)
          {
            $currentPayment = $customer->payments()->where('date', $payment->paymentDate->format('Y-m-d'))->first();

            if ($currentPayment == null)
            {
              $currentPayment = new Payment;

              $currentPayment->date = $payment->paymentDate; 
              $currentPayment->amount = $payment->amount; 
              $currentPayment->status = $payment->status;
              $currentPayment->payment_provider_id = $payment->provider;

              $customer->payments()->save($currentPayment);
            }
          }
        }
        else
        {
          //TODO: Log an issue to the system somehow.
          //Not a valid email address
        }
      }
    }

  }

	public function getCustomerPayments($startDate)
	{
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
      $member->type= $this->get($response, "L_TYPE".$i);

      if ($member->amount != null && $member->type == "Recurring Payment")
      {
        $members[] = $member;
      }

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

