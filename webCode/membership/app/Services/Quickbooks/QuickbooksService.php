<?php

namespace App\Services\PayPal;

use DateTime;
use DateInterval;

class QuickbokosService
{
	private $user;
	private $pwd;
	
	private $url;
	private $version;

	private $key;
	private $secret;
	private $server;

	public function __construct()
  	{
  		$this->key = env("QUICKBOOKS_KEY");
  		$this->secret = env("QUICKBOOKS_SECRET");
  		

	    $this->server = new QuickbooksAuth(array(
    		'identifier'   => 'your-identifier',
    		'secret'       => 'your-secret',
    		'callback_uri' => 'http://your-callback-uri/',
		));
  	}

  	public function findMember($email)
  	{
  		$startDate = new DateTime;
        $startDate->sub(new DateInterval("P30D"));
        $startDate = $startDate->format(DateTime::ATOM);

        $response = $this->searchTransactions($startDate, $email);

        $result = new PayingMemberSearchResult;

        $result->status = "Fail";

        if (array_key_exists("L_EMAIL0", $response))
        {
        	$result->email = $response["L_EMAIL0"];;
        	$result->name = $response["L_NAME0"];
        	$result->amount = $response["L_AMT0"];
        	$result->status = "Success";
        }
		
		return $result;
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
        $params = $params."&EMAIL=".urlencode($email);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);

        $response = curl_exec($ch);

        $paypalResponse = array();
        parse_str($response,$paypalResponse);
        return $paypalResponse;
	}
}

