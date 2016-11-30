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
use App\Mail\PendingRevokation;

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

    $this->updateCustomerStatus();
  }

	public function getCustomerPayments($startDate)
	{
    $qbo = new QuickbooksService;        
    $paypal = new PayPalService;

    $qboPayments = $qbo->getCustomerPayments($startDate);
    $paypalPayments = $paypal->searchTransactions($startDate, null);

    return array_merge($qboPayments, $paypalPayments);
  }

  public function updateCustomerStatus()
  {
    $query='Update customers c 
      inner join (Select customer_id, date, amount, payment_provider_id, (date + INTERVAL 1 MONTH) as \'next_payment\'
        from payments
        where created_at  >= CURDATE() && created_at < (CURDATE() + INTERVAL 1 DAY)) p
      on c.id = p.customer_id
      Set c.payment_provider_id = p.payment_provider_id,
        c.last_payment_date = p.date,
        c.last_payment_amount = p.amount,
          c.next_payment_date = p.next_payment;';
  
    \DB::update($query);

    $query = 'Update members m
      inner join customers c
      on m.customer_id = c.id
      set m.expire_date = (c.next_payment_date + INTERVAL 1 MONTH),
        m.member_status_id = 1
      where c.updated_at >= CURDATE() && c.updated_at < (CURDATE() + INTERVAL 1 DAY)
        and c.next_payment_date is not null;';

    \DB::update($query);
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
          and c.created_at < :customer_created
          and (p.status = \'Unknown\'
            or p.status is null);';

    $failedPayments = \DB::select($query, ['customer_created' =>$startDate, 'payment_date'=>$startDate, 'notification_date'=>$startDate]);

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

  public function pendingRevokation()
  {
    $notificationThreshold = new \DateTime;
    $notificationThreshold->sub(new \DateInterval("P7D"));
    $notificationThreshold = $notificationThreshold->format(\DateTime::ATOM);

    $expirationDate = new \DateTime;
    $expirationDate->add(new \DateInterval("P15D"));
    $expirationDate = $expirationDate->format(\DateTime::ATOM);

    $query = 'Select m.id, m.email, m.name, m.expire_date
      from members m
      left join member_notifications n 
      on m.id = n.member_id
        and n.notification_type_id = 4
        and n.notification_date >= :notification_threshold
      where n.id is null
        and m.expire_date < :expiration_date
          and m.member_status_id = 1;';

    $pending = \DB::select($query, ['notification_threshold' =>$notificationThreshold, 'expiration_date'=>$expirationDate]);

    foreach($pending as $p)
    {
      
      $member = Member::find($p->id);
      $notification = new MemberNotification;
      $notification->notification_type_id = 4;
      $notification->notification_date = new \DateTime;
      $member->memberNotifications()->save($notification);

      \Mail::to($member)
        ->bcc('info@hackrva.org')
        ->queue(new PendingRevokation($member));      
    }

  }

}