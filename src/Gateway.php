<?php

namespace Omnipay\SmartPay;

use Omnipay\Common\AbstractGateway;
use Omnipay\SmartPay\Message\CompletePurchaseRequest;
use Omnipay\SmartPay\Message\PurchaseRequest;

/**
 * Bank Muscat Payment Gateway
 */
class Gateway extends AbstractGateway
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'SmartPay';
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return [];
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getAccessCode()
    {
        return $this->getParameter('accessCode');
    }

    public function setAccessCode($accessCode)
    {
        return $this->setParameter('accessCode', $accessCode);
    }

    public function getWorkingKey()
    {
        return $this->getParameter('workingKey');
    }

    public function setWorkingKey($workingKey)
    {
        return $this->setParameter('workingKey', $workingKey);
    }

    /**
     * Purchase
     *
     * @param array $parameters Parameters
     *
     * @return Omnipay\SmartPay\Message\PurchaseRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(
            PurchaseRequest::class,
            $parameters
        );
    }

    /**
     * Complete a purchase process
     *
     * @param array $parameters
     *
     * @return Omnipay\SmartPay\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
