<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MailChimp
{
	private $key;
    private $url;
    private $list;
	
	public function __construct()
  	{
        
	    $this->key = env("MAILCHIMP_KEY");
	    $this->url = env("MAILCHIMP_URL");
        $this->list = env("MAILCHIMP_LIST_ID");  
  	}

    public function addSubscriber($email)
    {
        $auth = base64_encode('user:'.$this->key);
        $data = array(
            'email_address' => $email,
            'status' => 'subscribed'
            );
        $json = json_encode($data);
        
        $endpoint = $this->url.'lists/'.$this->list.'/members';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, TRUE);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json);

        $response = curl_exec($ch);
        
        return json_decode($response);
    }
}

