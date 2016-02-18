<?php

namespace Omnipay\YandexKassaMws\Message;

use Guzzle\Common\Event;
use Omnipay\Common\Exception\BadMethodCallException;
use Omnipay\Common\Exception\RuntimeException;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
	public $testEndpoint = 'https://penelope-demo.yamoney.ru:8083/webservice/mws/api/';
	public $liveEndpoint = 'https://penelope.yamoney.ru:443/webservice/mws/api/';


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

	protected function sendRequest($endpoint, $data = null, $contentType = 'application/x-www-form-urlencoded')
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
			array('Content-type' => $contentType),
			$data
		);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSL_VERIFYHOST, false);
		$httpResponse->getCurlOptions()->set(CURLOPT_SSLCERT, $this->getCertFile());
		$httpResponse->getCurlOptions()->set(CURLOPT_SSLKEY, $this->getKeyFile());

		if (!empty($this->getCertPass())) {
			$httpResponse->getCurlOptions()->set(CURLOPT_SSLCERTPASSWD, $this->getCertPass());
		}

		return $httpResponse->send();
	}

	/**
	 * @param $data
	 * @return string
	 * @throws RuntimeException
	 */
	protected function signPKCS7($data)
	{
		$descriptorSpec = array(
			array("pipe", "r"),
			array("pipe", "w"),
			array("pipe", "w")
		);

		try {
			$opensslCommand = 'openssl smime -sign -signer ' . $this->getCertFile() .
				' -inkey ' . $this->getKeyFile() . ' -nochain -nocerts -outform' .
				' PEM -nodetach -passin pass:' . $this->getCertPass();
			$process = proc_open($opensslCommand, $descriptorSpec, $pipes);

			if (is_resource($process)) {
				fwrite($pipes[0], $data);
				fclose($pipes[0]);
				$pkcs7 = stream_get_contents($pipes[1]);
				fclose($pipes[1]);
				$resCode = proc_close($process);
				if ($resCode != 0) {
					throw new RuntimeException('OpenSSL call failed:' . $resCode . '\n' . $pkcs7);
				}
				return $pkcs7;
			}
		} catch (RuntimeException $e) {
			throw $e;
		} catch (\Exception $e) {
			throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
		}
	}

	protected function getEndpoint()
	{
		return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}
}
