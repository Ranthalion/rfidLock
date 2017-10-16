<?php

namespace App\Services\PaymentProviders;

use DateTime;
use DateInterval;
use App\Models\CustomerPayment;

class PayPalService
{
	private $user;
	private $pwd;
	private $signature;
	private $url;
	private $version;

	public function __construct()
  	{
	    $this->user = env("PAYPAL_USER");
	    $this->pwd = env("PAYPAL_PWD");
	    $this->signature = env("PAYPAL_SIGNATURE");
	    $this->version = env("PAYPAL_VERSION");
	    $this->url = env("PAYPAL_URL");  
  	}

  	public function findMember($email)
  	{
  		$startDate = new DateTime;
        $startDate->sub(new DateInterval("P35D"));
        $startDate = $startDate->format(DateTime::ATOM);

        $payments = $this->searchTransactions($startDate, $email);

        return $payments;

        /*
        $result = new CustomerPayment;

        $result->status = "Fail";
        $result->provider = "PayPal";

        if (array_key_exists("L_EMAIL0", $response))
        {
        	$result->email = $response["L_EMAIL0"];
        	$result->name = $response["L_NAME0"];
        	$result->amount = $response["L_AMT0"];
        	$result->status = "Success";
        }
		
		return $result;
        */
  	}

	public function searchTransactions($startDate, $email)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $params = "USER=".urlencode($this->user);
        $params = $params."&PWD=".urlencode($this->pwd);
        $params = $params."&SIGNATURE=".urlencode($this->signature);
        $params = $params."&VERSION=".urlencode("204");
        $params = $params."&METHOD=TransactionSearch";
        $params = $params."&STARTDATE=".urlencode($startDate);
        
        if ($email != null)
        {
            $params = $params."&EMAIL=".urlencode($email);
        }
        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);

        $response = curl_exec($ch);

        $paypalResponse = array();
        parse_str($response,$paypalResponse);

        //TODO: Figure out if paypal has changed the stuff
        //if (isset($paypalResponse["L_ERRORCODE0"]))
        //{
        //    throw new \Exception('PayPal Error: '.$response);
        //}
        
        $payments = array();
        $i = 0;
        while(array_key_exists("L_TRANSACTIONID".$i, $paypalResponse))
        {
          $payment = new CustomerPayment;
          $payment->email = $this->get($paypalResponse, "L_EMAIL".$i);
          $payment->name = $this->get($paypalResponse, "L_NAME".$i);
          $payment->amount = $this->get($paypalResponse, "L_AMT".$i);
          $payment->paymentDate = $this->get($paypalResponse, "L_TIMESTAMP".$i);
          $payment->provider = "PayPal";
          $payment->status = $this->get($paypalResponse, "L_STATUS".$i);
          $payment->type= $this->get($paypalResponse, "L_TYPE".$i);

          if ($payment->amount != null && $payment->type == "Recurring Payment")
          {
            $payments[] = $payment;
          }

          $i = $i + 1;
        }

        return $payments;
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

