<?php

ini_set('include_path', get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
require_once "Zend/Json.php";

class VtigerOperations {

	private $endpointUrl = "http://www.ejwelch.org/webservice.php";
	private $username = "kchmela";
	private $userAccessKey = "6Rk1D2ODBnHksWri";


	public function __construct() {
		//authentication
		$response = file_get_contents($this->endpointUrl."?operation=getchallenge&username=".$this->username);
		$jsonResponse = Zend_JSON::decode($response);

		if($jsonResponse['success']==false)
            //handle the failure case.
            die('getchallenge failed:'.$jsonResponse['error']['errorMsg']);

        print_r($jsonResponse);

	}

}