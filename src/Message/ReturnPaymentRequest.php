<?php

namespace Omnipay\YandexKassaMws\Message;

class ReturnPaymentRequest extends AbstractRequest
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
		$this->validate(
			'requestDT', 'amount', 'clientOrderId', 'shopId', 'cause', 'invoiceId', 'currency'
		);

		return array(
			'amount' => $this->getAmount(),
			'clientOrderId' => $this->getClientOrderId(),
			'shopId' => $this->getShopId(),
			'cause' => $this->getCause(),
			'currency' => $this->getCurrency(),
			'invoiceId' => $this->getInvoiceId(),
			'requestDT' => $this->getRequestDT() instanceof \DateTime ?
				$this->getRequestDT()->format(DATE_RFC3339) : $this->getRequestDT()
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