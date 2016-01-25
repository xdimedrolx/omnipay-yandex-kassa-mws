<?php

namespace Omnipay\YandexKassaMws\Message;

class RepeatCardPaymentRequest extends AbstractRequest
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

	public function getOrderNumber()
	{
		return $this->getParameter('orderNumber');
	}

	public function setOrderNumber($orderNumber)
	{
		return $this->setParameter('orderNumber', $orderNumber);
	}

	public function getCVV()
	{
		return $this->getParameter('cvv');
	}

	public function setCVV($cvv)
	{
		return $this->setParameter('cvv', $cvv);
	}

	public function getData()
	{
		$this->validate('clientOrderId', 'invoiceId', 'amount', 'orderNumber');

		$data = array();
		$data['amount'] = !is_string($this->getAmount()) ?
			number_format($this->getAmount(), 2, '.', '') : $this->getAmount();
		$data['clientOrderId'] = $this->getClientOrderId();
		$data['invoiceId'] = $this->getInvoiceId();
		$data['orderNumber'] = $this->getOrderNumber();

		if (!empty($this->getCVV())) {
			$data['cvv'] = $this->getCVV();
		}

		return $data;
	}

	public function sendData($data)
	{
		$httpResponse = $this->sendRequest('repeatCardPayment', $data);

		return $this->response = new RepeatCardPaymentResponse($this, $httpResponse->xml());
	}
}