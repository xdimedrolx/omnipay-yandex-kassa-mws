<?php

namespace Omnipay\YandexKassaMws;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
	public function getName()
	{
		return 'Yandex Money Mws';
	}

	public function getDefaultParameters()
	{
		return array(
			'certFile' => '',
			'keyFile' => '',
			'certPass' => '',
			'shopId' => '',
			'testMode' => false
		);
	}

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

	public function returnPayment(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\YandexKassaMws\Message\ReturnPaymentRequest', $parameters);
	}

//	public function fetchOrders(array $parameters = array())
//	{
//		return $this->createRequest('\Omnipay\YandexKassaMws\Message\FetchOrdersRequest', $parameters);
//	}

	public function repeatCardPayment(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\YandexKassaMws\Message\RepeatCardPaymentRequest', $parameters);
	}

	public function confirmPayment(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\YandexKassaMws\Message\ConfirmPaymentRequest', $parameters);
	}

	public function cancelPayment(array $parameters = array())
	{
		return $this->createRequest('\Omnipay\YandexKassaMws\Message\CancelPaymentRequest', $parameters);
	}
}
