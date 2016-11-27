<?php

namespace App\Services;

use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;

use App\Models\CustomerPayment;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Member;
use App\Models\MemberNotification;
use App\Models\NotificationType;

use App\Services\CustomerDAL;
use App\Mail\FailedQuickbooksPayment;;

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

  public function getFailedPayments()
  {
    $startDate = new \DateTime;
    $startDate->sub(new \DateInterval("P1M"));
    $startDate = $startDate->format(\DateTime::ATOM);

    $query = 'Select m.id, m.email, m.name
        from customers c
        inner join payment_providers pp
        on c.payment_provider_id = pp.id
        inner join members m
        on c.id = m.customer_id
        left join payments p 
        on c.id = p.customer_id
          and p.date >= :payment_date
        left join member_notifications n 
        on m.id = n.member_id
          and n.notification_date >= :notification_date
        where pp.description = \'Quickbooks\'
          and n.id is null
          and (p.status = \'Unknown\'
            or p.status is null);';

    $failedPayments = \DB::select($query, ['payment_date'=>$startDate, 'notification_date'=>$startDate]);

    foreach($failedPayments as $payment)
    {
      
      $member = Member::find($payment->id);
      $notification = new MemberNotification;
      $notification->notification_type_id = 2;
      $notification->notification_date = new \DateTime;
      $member->memberNotifications()->save($notification);

      \Mail::to($member)
        ->bcc('info@hackrva.org')
        ->queue(new FailedQuickbooksPayment($member));      
    }
  }

}