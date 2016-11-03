<?php

namespace App\Services;
use DateTime;

class SlackInviter
{
	private $token;
    private $url;
	
	public function __construct()
  	{
	    $this->token = env("SLACK_TOKEN");
	    $this->url = env("SLACK_URL");  
  	}

    public function sendInvite($email, $name)
    {
        $timestamp = (new DateTime())->getTimestamp();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url.'?t='.$timestamp);
        curl_setopt($ch, CURLOPT_POST, TRUE);        
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        $params = "&email=".urlencode($email);
        $params = $params."&token=".urlencode($this->token);
        $params = $params."&first_name=".urlencode($name);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$params);

        $response = curl_exec($ch);

        return $response;
    }
}

