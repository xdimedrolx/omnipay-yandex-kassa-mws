<?php

namespace Omnipay\YandexKassaMws\Message;

use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
	public function __construct(RequestInterface $request, \SimpleXMLElement $data)
	{
		parent::__construct($request, $data);
	}

	public function isSuccessful()
	{
		return $this->getCode() == 0;
	}

	public function getCode()
	{
		return (int) $this->data->attributes()->status;
	}

	public function getError()
	{
		return (int) $this->data->attributes()->error;
	}

	public function getErrorMessage()
	{
		if (isset($this->data->attributes()->techMessage)) {
			return (string) $this->data->attributes()->techMessage;
		}
		return null;
	}
}