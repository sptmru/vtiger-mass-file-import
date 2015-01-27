<?php

class VtigerOperations {

	private $endpointUrl = "http://www.ejwelch.org/webservice.php";
	private $username = "kchmela";
	private $userAccessKey = "6Rk1D2ODBnHksWri";

	private $httpc = '';

	public function __construct() {
		$this->httpc = new http\Client();

		//authentication
		$this->httpc->GET($endpointUrl."?operation=getchallenge&username=".$username);
		$response = $this->httpc->currentResponse();
		$jsonResponse = Zend_JSON::decode($response['body']);

		if($jsonResponse['success']==false)
            //handle the failure case.
            die('getchallenge failed:'.$jsonResponse['error']['errorMsg']);

        print_r($jsonResponse);

	}

}