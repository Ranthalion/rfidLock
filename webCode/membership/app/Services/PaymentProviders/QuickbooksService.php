<?php

namespace App\Services\PaymentProviders;

require_once(dirname(__FILE__) . '/../../../v3-php-sdk-2.5.0/config.php');
require_once(PATH_SDK_ROOT.'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT.'DataService/DataService.php');
require_once(PATH_SDK_ROOT.'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT.'Utility/Configuration/ConfigurationManager.php');

use DateTime;
use DateInterval;
use App\Models\CustomerPayment;

class QuickbooksService
{
  private $accessToken;
  private $accessTokenSecret;
  private $consumerKey;
  private $consumerSecret;
  private $realmId;

	public function __construct()
	{
    $this->accessToken = env('QB_ACCESS_TOKEN');
    $this->accessTokenSecret = env('QB_ACCESS_TOKEN_SECRET');
    $this->consumerKey = env('QB_CONSUMER_KEY');
    $this->consumerSecret = env('QB_CONSUMER_SECRET');
    $this->realmId = env('QB_REALM_ID');
	}

	public function findMember($email)
	{
		$startDate = new DateTime;
    $startDate->sub(new DateInterval("P30D"));
    $startDate = $startDate->format(DateTime::ATOM);

    $response = $this->searchTransactions($startDate, $email);

    return $response;
  }

  private function getDataService()
  {
      //Specify QBO or QBD
    $serviceType = \IntuitServicesType::QBO;

    $requestValidator = new \OAuthRequestValidator($this->accessToken,
                                                  $this->accessTokenSecret,
                                                  $this->consumerKey,
                                                  $this->consumerSecret);

    $serviceContext = new \ServiceContext($this->realmId, $serviceType, $requestValidator);
    if (!$serviceContext)
      exit("Problem while initializing ServiceContext.\n");

    // Prep Data Services
    $dataService = new \DataService($serviceContext);
    if (!$dataService)
      exit("Problem while initializing DataService.\n");

    return $dataService;
  }

  public function searchTransactions($startDate, $email)
  {
    $dataService = $this->getDataService();

    $customers = $dataService->Query("Select Id, FullyQualifiedName from Customer where Active=true and PrimaryEmailAddr='".$email."'");
    
    $payments = array();
    
    if (count($customers) == 1)
    {
      $customer = $customers[0];
      
      $payment = new CustomerPayment;
      $payment->name = $customer->FullyQualifiedName;
      $payment->email = $email;

      $salesReceipts = $dataService->Query("Select TxnDate, TotalAmt, BillEmail.*, CreditCardPayment.*  from SalesReceipt where CustomerRef = '".$customer->Id."' and TxnDate > '".$startDate."'");

      if(count($salesReceipts) > 0)
      {
        foreach($salesReceipts as $salesReceipt)
        {
          if ($salesReceipt->CreditCardPayment->CreditChargeResponse->Status == 'Completed')
          {
            $payment->amount = $salesReceipt->TotalAmt;
            $payment->status = 'Success';
            $payment->paymentDate = $salesReceipt->TxnDate;
            $payment->provider = 'Quickbooks';
            $payment->type = "Recurring Payment";
            $payments[] = $payment;
          }
        }
        //TODO: [ML] What if the customer is found, but there are no completed payments?
      }
    }
      
    //TODO: [ML] What if there are multiple customers using the same email address?

    return $payments;
	}

  public function getFailedTransactions($startDate)
  {
    //$dataService = $this->getDataService();
    
    //$salesReceipts = $dataService->Query("Select TxnDate, TotalAmt, CreditCardPayment.* from SalesReceipt where TxnDate > '".$startDate."' MAXRESULTS 1000");

    $salesReceipts = $this->getTransactions($startDate);

    $failedTransactions = array_filter($salesReceipts, function($r){
      if ($r->CreditCardPayment == null)
        return false;
      if ($r->CreditCardPayment->CreditChargeResponse == null)
        return false;
      return $r->CreditCardPayment->CreditChargeResponse->Status != "Completed";
    });
    //TODO: [ML] This does not help me find members that didn't even have a payment processed.
    dd($failedTransactions);

  }

  public function getTransactions($startDate)
  {
    $dataService = $this->getDataService();
    
    $salesReceipts = $dataService->Query("Select TxnDate, TotalAmt, BillEmail.*, CreditCardPayment.* from SalesReceipt where TxnDate > '".$startDate."' MAXRESULTS 1000");

    $keyed = array();
    
    if ($salesReceipts != null)
    {
    
      usort($salesReceipts, function($x, $y) 
        {
          if ( $x->TxnDate == $y->TxnDate )
            return 0;
          else if ( $x->TxnDate > $y->TxnDate )
            return -1;
          else
            return 1;
        });

      foreach($salesReceipts as $salesReceipt)
      {
        if($salesReceipt->BillEmail != null)
        {
          $keyed[$salesReceipt->BillEmail->Address] = $salesReceipt;
        }
        else
        {
          $keyed[] = $salesReceipt;
        }
      }
    }

    return $keyed;

  }

  public function getActiveMembers()
  {
    $dataService = $this->getDataService();

    $customers = $dataService->Query("Select Id, MetaData.CreateTime, GivenName, FamilyName, DisplayName, PrimaryEmailAddr from Customer where Active = true MAXRESULTS 1000");

    $keyed = array();
    foreach($customers as $customer)
    {
      if($customer->PrimaryEmailAddr != null)
      {
        $keyed[$customer->PrimaryEmailAddr->Address] = $customer;
      }
      else
      {
        $keyed[] = $customer;
      }
    }

    return $keyed;
  }

  public function getCustomerPayments($startDate)
  {

    $customers = $this->getActiveMembers();
    $transactions = $this->getTransactions($startDate);

    $payments = array();

    foreach($customers as $email => $customer)
    {
      $payment = new CustomerPayment;
      $payment->email = $email;
      $payment->name = $customer->GivenName . " " . $customer->FamilyName;
      $payment->provider="Quickbooks";

      if(isset($transactions[$email]))
      {
        $payment->paymentDate = $transactions[$email]->TxnDate;
        if ($transactions[$email]->CreditCardPayment != null)
        {
          if($transactions[$email]->CreditCardPayment->CreditChargeInfo != null)
          {
            $payment->amount=$transactions[$email]->CreditCardPayment->CreditChargeInfo->Amount;
          }
          if($transactions[$email]->CreditCardPayment->CreditChargeResponse != null)
          {
            $payment->status= $transactions[$email]->CreditCardPayment->CreditChargeResponse->Status;
          }
        } 
        else
        {
          $payment->status = "???";
        }
      }
      else
      {
        $payment->status = "Expired";
      }
      $payments[] = $payment;
    }
    return $payments;
  }

}

