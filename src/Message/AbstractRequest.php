<?php

namespace Omnipay\YandexKassaMws\Message;

use Guzzle\Common\Event;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
	public $testEndpoint = 'https://penelope-demo.yamoney.ru:8083/webservice/mws/api/';
	public $liveEndpoint = '';


	public function getCertFile()
	{
		return $this->getParameter('certFile');
	}

	public function setCertFile($certFile)
	{
		return $this->setParameter('certFile', $certFile);
	}

	public function getKeyFile()
	{
		return $this->getParameter('keyFile');
	}

	public function setKeyFile($keyFile)
	{
		return $this->setParameter('keyFile', $keyFile);
	}

	public function getCertPass()
	{
		return $this->getParameter('certPass');
	}

	public function setCertPass($certPass)
	{
		return $this->setParameter('certPass', $certPass);
	}

	public function getShopId()
	{
		return $this->getParameter('shopId');
	}

	public function setShopId($shopId)
	{
		return $this->setParameter('shopId', $shopId);
	}

	protected function sendRequest($endpoint, $data = null)
	{
		// don't throw exceptions for 4xx errors
		$this->httpClient->getEventDispatcher()->addListener(
			'request.error',
			function ($event) {
				if ($event['response']->isClientError()) {
					$event->stopPropagation();
				}
			}
		);

		$data = is_array($data) ? http_build_query($data) : $data;

		$httpResponse = $this->httpClient->createRequest(
			'POST',
			$this->getEndpoint() . $endpoint,
			array(),
			$data
		);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSLKEYTYPE, "DER");
		$httpResponse->getCurlOptions()->set(CURLOPT_SSLCERT, $this->getCertFile());
		$httpResponse->getCurlOptions()->set(CURLOPT_SSLKEY, $this->getKeyFile());

		if (!empty($this->getCertPass())) {
			$httpResponse->getCurlOptions()->set(CURLOPT_SSLCERTPASSWD, $this->getCertPass());
		}

		return $httpResponse->send();
	}

	protected function getEndpoint()
	{
		return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}
}
