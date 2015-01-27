<?php

require('classes/VtigerOperations.php');

$vtiger = new VtigerOperations();


$filesize = filesize("test_import.pdf");

//just testing
$documentParams = array(
	'notes_title' => 'Test Import',
	'assigned_user_id' => $vtiger->userId,
	'filetype' => 'file',
	'filesize' => $filesize,
	'filelocationtype' => 'I',
	'filestatus' => 1,
	'filename' => 'test_import.pdf',
	'folderid' => '22x277'
	);



$testOutput = $vtiger->importDocument($documentParams, "test_import.pdf");
print_r($testOutput);

?>