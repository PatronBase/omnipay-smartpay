<?php

namespace Omnipay\SmartPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Bank Muscat Smart Pay Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    protected $rawData;

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

        foreach (['encResp', 'orderNo'] as $key) {
            if (empty($data[$key])) {
                throw new InvalidResponseException('Invalid response from payment gateway ['.$key.'] missing');
            }
        }

        try {
            $crypto = new Crypto();

            $decryptedString = $crypto->decrypt($this->data['encResp'], $this->getRequest()->getWorkingKey());
        } catch (Exception $exception) {
            throw new InvalidResponseException('Invalid response from payment gateway');
        }

        if ( empty($decryptedString) ) {
            throw new InvalidResponseException('Invalid response from payment gateway');
        }

        $orderData = [];

        foreach (explode('&', $decryptedString) as $item) {
            [$key, $value] = explode('=', $item);
            $orderData[$key] = $value;
        }

        foreach (['order_id', 'tracking_id', 'status_message', 'order_status'] as $key) {
            if (empty($orderData[$key])) {
                throw new InvalidResponseException('Invalid response from payment gateway ['.$key.'] missing');
            }
        }

        $this->rawData = $data;
        $this->data = $orderData;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getOrderStatus() === 'Success';
    }

    /**
     * Get the order status if available.
     *
     * @return null|string
     */
    public function getOrderStatus()
    {
        return $this->getKey('order_status');
    }


    public function getMessage()
    {
        return $this->getKey('status_message');
    }

    /**
     * Get the transaction identifier if available.
     *
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->getKey('tracking_id');
    }

    /**
     * Get the merchant-supplied transaction identifier if available.
     *
     * @return null|string
     */
    public function getTransactionId()
    {
        return $this->getKey('order_id');
    }

    /**
     * Get the card number if available.
     *
     * @return null|string
     */
    public function getCardNumber()
    {
        return $this->getKey('merchant_param6');
    }

    /**
     * Get the card type if available.
     *
     * @return null|string
     */
    public function getCardType()
    {
        return $this->getKey('card_type') ?? $this->getKey('card_name');
    }

    /**
     * Get the card expiry if available.
     *
     * @return null|string
     */
    public function getCardExpiry()
    {
        return $this->getKey('merchant_param7');
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
        return $this->data[$key] ?? null;
    }
}
