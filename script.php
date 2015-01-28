<?php

require('classes/VtigerOperations.php');
require('classes/Documents.php');

$vtiger = new VtigerOperations();

$documents = new Documents;
$documentsList = $documents->getDocumentList();


foreach($documentsList as $name) {
	$noUrlName = urldecode($name);
	if(substr($noUrlName, -3) === "pdf" ) {
		file_put_contents("docs/".$noUrlName, fopen($documents->documentsUrl.$name, 'r'));
		$filesize = filesize("docs/".$noUrlName);

		$documentParams = array(
			'notes_title' => $noUrlName,
			'assigned_user_id' => $vtiger->userId,
			'filetype' => 'file',
			'filesize' => $filesize,
			'filelocationtype' => 'I',
			'filestatus' => 1,
			'filename' => $noUrlName,
			'folderid' => '22x277'
		);
		$testOutput = $vtiger->importDocument($documentParams, "docs/".$noUrlName);
		print_r($testOutput);
	}
		
}

?>