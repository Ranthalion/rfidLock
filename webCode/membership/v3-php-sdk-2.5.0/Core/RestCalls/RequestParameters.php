<?php 

/**
 * Parameters for calling Rest calls.
 */	        
class RequestParameters
{
	/**
	 * The resource URI.
	 * @var string 
	 */	        
	public $ResourceUri;        

	/**
	 * The http verb.
	 * @var string 
	 */	        
	public $HttpVerbType;        

	/**
	 * The type of the content.
	 * @var string 
	 */	        
	public $ContentType;        

	/**
	 * the name of the API.
	 * @var string 
	 */	        
	public $ApiName;        

	public function __construct($resourceUri, $verb, $contentType, $apiName=NULL)
	{
		$this->ResourceUri = $resourceUri;
		$this->HttpVerbType = $verb;
		$this->ContentType = $contentType;
		$this->ApiName = $apiName;
	}

}

?>