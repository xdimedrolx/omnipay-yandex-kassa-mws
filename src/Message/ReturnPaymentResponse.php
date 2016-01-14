<?php

namespace Omnipay\YandexKassaMws\Message;

class ReturnPaymentResponse extends AbstractResponse
{
	public function getData()
	{
		return array(
			'clientOrderId' => (int) $this->data->attributes()->clientOrderId,
			'status' => $this->getCode(),
			'error' => $this->getError(),
			'processedDT' => new \DateTime((string) $this->data->attributes()->processedDT),
		);
	}
}