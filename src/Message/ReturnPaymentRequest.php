<?php

namespace Omnipay\YandexKassaMws\Message;

class CancelPaymentRequest extends AbstractRequest
{
	public function getClientOrderId()
	{
		return $this->getParameter('clientOrderId');
	}

	public function setClientOrderId($clientOrderId)
	{
		return $this->setParameter('clientOrderId', $clientOrderId);
	}

	public function getInvoiceId()
	{
		return $this->getParameter('invoiceId');
	}

	public function setInvoiceId($invoiceId)
	{
		return $this->setParameter('invoiceId', $invoiceId);
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

	public function getCause()
	{
		return $this->getParameter('cause');
	}

	public function setCause($cause)
	{
		return $this->setParameter('cause', $cause);
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
		$this->validate('amount', 'clientOrderId', 'shopId', 'cause', 'invoiceId', 'currency');

		return array(
			'amount' => !is_string($this->getAmount()) ?
				number_format($this->getAmount(), 2, '.', '') : $this->getAmount(),
			'clientOrderId' => $this->getClientOrderId(),
			'shopId' => $this->getShopId(),
			'cause' => $this->getCause(),
			'currency' => $this->getCurrency(),
			'invoiceId' => $this->getInvoiceId(),
			'requestDT' => $this->getRequestDT() instanceof \DateTime ?
				$this->getRequestDT()->format(DATE_ISO8601) : $this->getRequestDT()
		);
	}

	protected function getDataAsXml($data)
	{
		$body = '<?xml version="1.0" encoding="UTF-8"?>';
		$body .= '<returnPaymentRequest ';
		foreach($data AS $param => $value) {
			$body .= $param . '="' . $value . '" ';
		}
		$body .= '/>';

		return $body;
	}

	public function sendData($data)
	{
		$httpResponse = $this->sendRequest(
			'returnPayment',
			$this->signPKCS7($this->getDataAsXml($data)),
			'application/pkcs7-mime'
		);

		return $this->response = new ReturnPaymentResponse($this, $httpResponse->xml());
	}
}