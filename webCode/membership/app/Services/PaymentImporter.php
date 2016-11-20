<?php

namespace App\Services;

use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Payment;

use App\Services\CustomerDAL;

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

    $dal = new CustomerDAL;

    foreach($customer_payments as $payment)
    {
      $customer = null;
      
      if ($payment->paymentDate != null && $payment->amount != null)
      {        
        if (filter_var($payment->email, FILTER_VALIDATE_EMAIL))
        {

          $payment->paymentDate = new \DateTimeImmutable($payment->paymentDate);          
          $dal->SaveCustomerPayment($payment);
        }
        else
        {
          //TODO: Log an issue to the system somehow.
          //Not a valid email address, but a payment was received.
        }
      }
    }

  }

	public function getCustomerPayments($startDate)
	{
    $qbo = new QuickbooksService;        
    $paypal = new PayPalService;

    $qboPayments = $qbo->getCustomerPayments($startDate);
    $paypalPayments = $paypal->searchTransactions($startDate, null);

    return array_merge($qboPayments, $paypalPayments);
  }
}