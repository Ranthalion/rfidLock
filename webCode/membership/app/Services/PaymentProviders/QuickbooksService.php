<?php

namespace App\Services\PaymentProviders;

require_once('./../v3-php-sdk-2.5.0/config.php');
require_once(PATH_SDK_ROOT.'Core/ServiceContext.php');
require_once(PATH_SDK_ROOT.'DataService/DataService.php');
require_once(PATH_SDK_ROOT.'PlatformService/PlatformService.php');
require_once(PATH_SDK_ROOT.'Utility/Configuration/ConfigurationManager.php');

use DateTime;
use DateInterval;
use App\Models\PayingMemberSearchResult;

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

  public function searchTransactions($startDate, $email)
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

    $customers = $dataService->Query("Select * from Customer where Active=true and PrimaryEmailAddr='".$email."'");
    
    $result = new PayingMemberSearchResult;
    $result->status = "Fail";
    $result->provider = "Quickbooks";

    if (count($customers) == 1)
    {
      $customer = $customers[0];

      $result->name = $customer->FullyQualifiedName;
      $result->email=$email;
       
      $salesReceipts = $dataService->Query("Select * from SalesReceipt where CustomerRef = '".$customer->Id."' and TxnDate > '".$startDate."'");

      if(count($salesReceipts) > 0)
      {
        foreach($salesReceipts as $salesReceipt)
        {
          if ($salesReceipt->CreditCardPayment->CreditChargeResponse->Status == 'Completed')
          {
            $result->amount = $salesReceipt->TotalAmt;
            $result->status = 'Success';
            return $result;
          }
        }
        //TODO: [ML] What if the customer is found, but there are no completed payments?
      }
    }
      
    //TODO: [ML] What if there are multiple customers using the same email address?
    return $result;
        		
	}
}

