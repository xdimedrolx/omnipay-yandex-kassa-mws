<?php

namespace Omnipay\YandexKassaMws\Message;


class ConfirmPaymentRequest extends AbstractRequest
{
	public function getOrderId()
	{
		return $this->getParameter('orderId');
	}

	public function setOrderId($orderId)
	{
		return $this->setParameter('orderId', $orderId);
	}

	public function getAmount()
	{
		return $this->getParameter('amount');
	}

	public function setAmount($amount)
	{
		return $this->setParameter('amount', $amount);
	}

	public function getCurrency()
	{
		return $this->getParameter('currency');
	}

	public function setCurrency($currency)
	{
		return $this->setParameter('currency', $currency);
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
			'amount' => $this->getAmount(),
			'currency' => $this->getCurrency(),
			'orderId' => $this->getOrderId(),
			'requestDT' => $this->getRequestDT() instanceof \DateTime ?
				$this->getRequestDT()->format(DATE_ISO8601) : $this->getRequestDT()
		);
	}

	public function sendData($data)
	{
		$httpResponse = $this->sendRequest('confirmPayment', $data);

		return $this->response = new ConfirmPaymentResponse($this, $httpResponse->xml());
	}
}