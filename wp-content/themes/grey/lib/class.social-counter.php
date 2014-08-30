<?php

class SocialCounter {
	public function __construct() {
		$this->reset();
	}

	protected function reset() {
		$this->hasError = false;
		$this->errorMessages = array();
	}

	protected function makeCurlCall($url) {
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_REFERER, 'http://www.hudsongray.com');
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	    $output = curl_exec($ch);
	    curl_close($ch);

	 	$data = json_decode($output);

	 	if (is_null($data)) {
	 		$this->hasError = true;
	 		return false;
	 	} else {
	 		return $data;
	 	}
	}

	protected function setError($message) {
		$this->hasError = true;
		array_push($this->errorMessages, $message);
	}

	public function getTwitterCountFor($pageUrl) {
		$twitterUrl = 'https://cdn.api.twitter.com/1/urls/count.json?url=' . $pageUrl;

	 	$data = $this->makeCurlCall($twitterUrl);
	 	$result = ($data && $data->count) ? (int)$data->count : 0;

	 	return $result;
	}

	public function getFacebookCountFor($pageUrl) {
		$url = 'http://graph.facebook.com/?id=' . $pageUrl;

	 	$data = $this->makeCurlCall($url);
	 	$result = ($data && $data->shares) ? (int)$data->shares : 0;

	 	return $result;
	}

	public function getPlusCountFor($pageUrl) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($pageUrl).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$output = curl_exec ($curl);
		curl_close ($curl);

	 	$data = json_decode($output);

	 	if (is_null($data)) {
	 		$this->hasError = true;
	 	}

		$result = ($data && $data[0]->result->metadata->globalCounts->count) ? (int)$data[0]->result->metadata->globalCounts->count : 0;

		return $result;
	}

	public function execute($pageUrl, $services) {
		$result = array();

		$this->reset();

		foreach ($services as $service) {
			$methodName = sprintf('get%sCountFor', ucfirst($service));
			if (method_exists($this, $methodName)) {
				$result[$service] = $this->$methodName($pageUrl);
			} else {
				$this->setError("Unsupported service '$service'");
			}
		}

		return $result;
 	}
}

?>