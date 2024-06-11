<?php

namespace Omnipay\SmartPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * CyberSource Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Constructor
     *
     * @param RequestInterface $request the initiating request.
     * @param mixed $data
     *
     * @throws InvalidResponseException If merchant data or order number is missing, or signature does not match
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * Is the response successful?
     *
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Get the transaction identifier if available.
     *
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->getKey('transaction_id');
    }

    /**
     * Get the merchant-supplied transaction identifier if available.
     *
     * @return null|string
     */
    public function getTransactionId()
    {
        return $this->getKey('req_reference_number');
    }

    /**
     * Helper method to get a specific response parameter if available.
     *
     * @param string $key The key to look up
     *
     * @return null|mixed
     */
    protected function getKey($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
