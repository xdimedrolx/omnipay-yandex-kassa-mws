<?php

namespace Omnipay\YandexKassaMws\Message;

class CancelPaymentRequest extends AbstractRequest
{
	public function getOrderId()
	{
		return $this->getParameter('orderId');
	}

	public function setOrderId($orderId)
	{
		return $this->setParameter('orderId', $orderId);
	}

	public function getRequestDT()
	{
		return $this->getParameter('requestDT');
	}

	public function setRequestDT($requestDT)
	{
		return $this->setParameter('requestDT', $requestDT);
	}

	public function getData()
	{
		return array(
			'orderId' => $this->getOrderId(),
			'requestDT' => $this->getRequestDT() instanceof \DateTime ?
				$this->getRequestDT()->format(DATE_ISO8601) : $this->getRequestDT()
		);
	}

	public function sendData($data)
	{
		$httpResponse = $this->sendRequest('cancelPayment', $data);

		return $this->response = new CancelPaymentResponse($this, $httpResponse->xml());
	}
}