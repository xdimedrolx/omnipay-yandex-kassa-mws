<?php

namespace Omnipay\YandexKassaMws\Message;

class CancelPaymentResponse extends AbstractResponse
{
	public function getData()
	{
		return array(
			'orderId' => (int) $this->data->attributes()->orderId,
			'status' => $this->getCode(),
			'error' => $this->getError(),
			'processedDT' => new \DateTime((string) $this->data->attributes()->processedDT),
		);
	}
}