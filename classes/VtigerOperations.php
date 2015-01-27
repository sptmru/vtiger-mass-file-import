<?php

ini_set('include_path', get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
require_once "Zend/Json.php";

class VtigerOperations {

	private $endpointUrl = "http://www.ejwelch.org/webservice.php";
	private $username = "kchmela";
	private $userAccessKey = "6Rk1D2ODBnHksWri";

	private $challengeToken = "";
	private $generatedKey = "";

	private $sessionId = "";
	private $userId = "";

	public function __construct() {
		//authentication
		$response = file_get_contents($this->endpointUrl."?operation=getchallenge&username=".$this->username);
		$jsonResponse = Zend_JSON::decode($response);

		if($jsonResponse['success']==false)
            die('getchallenge failed:'.$jsonResponse['error']['errorMsg']);

        $this->challengeToken = $jsonResponse['result']['token'];
        $this->generatedKey = md5($this->challengeToken.$this->userAccessKey);


        $post_params = array(
        	'operation' => 'login',
        	'username' => $this->username,
        	'accessKey' => $this->generatedKey);

        $response = file_get_contents($this->endpointUrl, false, stream_context_create(array(
        	'http' => array(
        		'method' => 'POST',
        		'header' => 'Content-type: application/x-www-form-urlencoded',
        		'content' => http_build_query($post_params)
        		))));
        $jsonResponse = Zend_JSON::decode($response);

        if($jsonResponse['success']==false)
            die('getchallenge failed:'.$jsonResponse['error']['errorMsg']);


        $this->sessionId = $jsonResponse['result']['sessionName'];
        $this->userId = $jsonResponse['result']['userId'];
	}

	public function importDocument($documentParams) {
		$moduleName = "Documents";
		$objectJson = Zend_JSON::encode($documentParams);
		$post_params = array(
			'sessionName' => $this->session_id,
			'operation' => 'create',
			'element' => $objectJson,
			'elementType' => $moduleName);

		$response = file_get_contents($this->endpointUrl, false, stream_context_create(array(
        	'http' => array(
        		'method' => 'POST',
        		'header' => 'Content-type: application/x-www-form-urlencoded',
        		'content' => http_build_query($post_params)
        		))));
        $jsonResponse = Zend_JSON::decode($response);
        $savedObject = $jsonResponse['result'];
        $id = $savedObject['id']; 
        $id = str_replace("13x", "", $id);
        return $id;
	}

}