<?php

namespace Omnipay\YandexKassaMws\Message;

class RepeatCardPaymentResponse extends AbstractResponse
{
	public function getTransactionId()
	{
		return (string) $this->data->attributes()->clientOrderId;
	}

	public function getInvoiceId()
	{
		return isset($this->data->attributes()->invoiceId) ? (int) $this->data->attributes()->invoiceId : null;
	}

	public function getData()
	{
		return array(
			'clientOrderId' => $this->getTransactionId(),
			'status' => $this->getCode(),
			'error' => $this->getError(),
			'processedDT' => new \DateTime((string) $this->data->attributes()->processedDT),
			'techMessage' => (string) $this->data->attributes()->techMessage,
			'invoiceId' => $this->getInvoiceId()
		);
	}
}
