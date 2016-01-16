<?php
namespace DiegoSCosta\Skoob;

abstract class Service
{
	protected $url = "http://www.skoob.com.br/v1";
	protected $api = array();
	protected $errors = array();

	public function setErrors($message, $skoob = 0)
	{
		$error = array('message' => $message, 'skoob' => $skoob);
		
		array_push($this->errors, $error);
		
		return false;
	}

	public function showErrors($skoobErrors = false)
	{
		foreach ($this->errors as $err) {
			$message = (!$skoobErrors) ? $err['message'] : $err['skoob'];
			throw new \Exception($message);
		}

		return count($this->errors);
	}

	private function getUriContents($uri)
	{
		$contents = file_get_contents($uri);

		return ($contents) ? $contents : $this->setErrors('Failed to fet content of ' . $uri);
	}

	private function parseUrlContents($contents)
	{
		$decodedContent = json_decode($contents);

		if(!$decodedContent->success)
			return $this->setErrors('Error to parse content', $decodedContent->cod_description);

		return $decodedContent;
	}

	protected function getSkoobContentFor($apiName, $customParams = '')
	{
		if(!array_key_exists($apiName, $this->api))
			return $this->setErrors('Skoob Content for ' . $apiName . ' is not defined.');

		$contentUrl = $this->api[$apiName] . $customParams;
		$getContents = $this->getUriContents($contentUrl);
		
		return $this->parseUrlContents($getContents);
	}

	public function showDebug()
	{
		var_dump($this->api);
		var_dump($this->errors);
	}

}