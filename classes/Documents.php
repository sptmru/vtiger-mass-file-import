<?php

require_once('simple_html_dom.php');

class Documents {

	public $documentsUrl = "http://www.ejwelch.org/quotes%20from%20sharepoint/Quotes%20from%20SP/";

	public function getDocumentList() {
		$result = array();
		$html = file_get_html($this->documentsUrl);
		foreach ($html->find('a') as $document) {
			$result[] = $document->href;
		}
		return $result;
	}
}