<?php

ini_set('include_path', get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
error_reporting(E_ALL);
require_once "Zend/Json.php";

class VtigerOperations {

	private $endpointUrl = "http://www.ejwelch.org/webservice.php";
	private $username = "kchmela";
	private $userAccessKey = "6Rk1D2ODBnHksWri";

	private $challengeToken = "";
	private $generatedKey = "";

	public $sessionId = "";
	public $userId = "";

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
/*
	public function importDocument($documentParams, $filename) {
		$moduleName = "Documents";
		$objectJson = Zend_JSON::encode($documentParams);
		$post_params = array(
			'sessionName' => $this->sessionId,
			'operation' => 'create',
			'element' => $objectJson,
			'elementType' => $moduleName,
			'file' => $filename);

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
        return $response;
	}*/

	public function importDocument($documentParams, $filename) {
		$fileNameWithPath = realpath($filename);
		$moduleName = "Documents";
		$objectJson = Zend_JSON::encode($documentParams);
		$post_params = array(
			'sessionName' => $this->sessionId,
			'operation' => 'create',
			'element' => $objectJson,
			'elementType' => $moduleName,
			'file' => '@'.$fileNameWithPath
			);

		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, $this->endpointUrl);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ci, CURLOPT_FORBID_REUSE, 0);
    	curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'POST');
    	curl_setopt($ci, CURLOPT_POSTFIELDS, $post_params);
    	$result = curl_exec($ci);
    	return $result;
	}

	public function sendQuery($query) {
		$response = file_get_contents($this->endpointUrl."?operation=query&sessionName=".$this->sessionId."&query=".$query);
		return $response;
	}

}