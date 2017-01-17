<?php

namespace App\Services;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Payment;

class CustomerDAL
{

	public function __construct()
	{

  }

  public function SaveCustomerPayment(CustomerPayment $payment)
  {
    $customer = null;

    if ($payment->paymentDate != null && $payment->amount != null)
    {        
      if (filter_var($payment->email, FILTER_VALIDATE_EMAIL))
      {
        if (is_string($payment->paymentDate))
        {
          $payment->paymentDate = new \DateTimeImmutable($payment->paymentDate);
        }
        $nextPayment = $payment->paymentDate->add(new \DateInterval("P1M"));

        if ($payment->provider == 'PayPal')
          $payment->provider = 2;
        else
          $payment->provider = 1;

        $customer = Customer::updateOrCreate(
          [ 'email' => $payment->email], 
          ['name' => $payment->name,
            'last_payment_date' => $payment->paymentDate, 
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
    }
    return $customer;
  }
}