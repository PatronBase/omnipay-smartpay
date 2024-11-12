<?php

namespace Omnipay\SmartPay\Message;

use Illuminate\Http\Request;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Omnipay Bank Muscat Purchase Request
 */
class PurchaseRequest extends AbstractRequest {

    const EP_HOST_TEST = 'https://mti.bankmuscat.com:6443/transaction.do?command=initiateTransaction';
    const EP_HOST_LIVE = 'https://smartpaytrns.bankmuscat.com/transaction.do?command=initiateTransaction';

    public function initialize(array $parameters = []) 
    {
        return parent::initialize($parameters);
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

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($orderId)
    {
        return $this->setParameter('orderId', $orderId);
    }

    public function getMerchantParameter1()
    {
        return $this->getParameter('merchantParameter1');
    }

    public function setMerchantParameter1($parameter)
    {
        return $this->setParameter('merchantParameter1', $parameter);
    }

    public function getMerchantParameter2()
    {
        return $this->getParameter('merchantParameter2');
    }

    public function setMerchantParameter2($parameter)
    {
        return $this->setParameter('merchantParameter2', $parameter);
    }

    public function getMerchantParameter3()
    {
        return $this->getParameter('merchantParameter3');
    }

    public function setMerchantParameter3($parameter)
    {
        return $this->setParameter('merchantParameter3', $parameter);
    }

    public function getMerchantParameter4()
    {
        return $this->getParameter('merchantParameter4');
    }

    public function setMerchantParameter4($parameter)
    {
        return $this->setParameter('merchantParameter4', $parameter);
    }

    public function getMerchantParameter5()
    {
        return $this->getParameter('merchantParameter5');
    }

    public function setMerchantParameter5($parameter)
    {
        return $this->setParameter('merchantParameter5', $parameter);
    }

    public function setBillingName($billingName)
    {
        return $this->setParameter('billingName', $billingName);
    }

    public function getBillingName()
    {
        return $this->getParameter('billingName');
    }

    public function setBillingAddress($billingAddress)
    {
        return $this->setParameter('billingAddress', $billingAddress);
    }

    public function getBillingAddress()
    {
        return $this->getParameter('billingAddress');
    }

    public function setBillingCity($billingCity)
    {
        return $this->setParameter('billingCity', $billingCity);
    }

    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    public function setBillingState($billingState)
    {
        return $this->setParameter('billingState', $billingState);
    }

    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    public function setBillingZip($billingZip)
    {
        return $this->setParameter('billingZip', $billingZip);
    }

    public function getBillingZip()
    {
        return $this->getParameter('billingZip');
    }

    public function setBillingCountry($billingCountry)
    {
        return $this->setParameter('billingCountry', $billingCountry);
    }

    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    public function setBillingEmail($billingEmail)
    {
        return $this->setParameter('billingEmail', $billingEmail);
    }

    public function getBillingEmail()
    {
        return $this->getParameter('billingEmail');
    }

    public function setBillingTel($billingTel)
    {
        return $this->setParameter('billingTel', $billingTel);
    }

    public function getBillingTel()
    {
        return $this->getParameter('billingTel');
    }

    /**
     * Get data
     *
     * @access public
     * @return array
     */
    public function getData() 
    {
        $this->validate(
            'merchantId', 'accessCode', 'workingKey',
            'amount', 'currency', 'returnUrl', 'cancelUrl'
        );

        $data = [];

        $data['merchant_id'] = $this->getMerchantId();
        $data['access_code'] = $this->getAccessCode();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrency();
        $data['order_id'] = $this->getTransactionId();
        $data['redirect_url'] = $this->getReturnUrl();
        $data['cancel_url'] = $this->getCancelUrl();
        $data['merchant_param1'] = $this->getMerchantParameter1();
        $data['merchant_param2'] = $this->getMerchantParameter2();
        $data['merchant_param3'] = $this->getMerchantParameter3();
        $data['merchant_param4'] = $this->getMerchantParameter4();
        $data['merchant_param5'] = $this->getMerchantParameter5();
        $data['billing_name'] = $this->getBillingName();
        $data['billing_address'] = $this->getBillingAddress();
        $data['billing_city'] = $this->getBillingCity();
        $data['billing_state'] = $this->getBillingState();
        $data['billing_zip'] = $this->getBillingZip();
        $data['billing_country'] = $this->getBillingCountry();
        $data['billing_email'] = $this->getBillingEmail();
        $data['billing_tel'] = $this->getBillingTel();

        if ( $this->getCard() ) {
            $data['card_number'] = $this->getCard()->getNumber();
            $data['expiry_month'] = $this->getCard()->getExpiryMonth();
            $data['expiry_year'] = $this->getCard()->getExpiryYear();
            $data['cvv_number'] = $this->getCard()->getCvv();
        }

        return $data;
    }

    /**
     * Send data
     *
     * @param array $data Data
     *
     * @access public
     * @return PurchaseResponse
     */
    public function sendData($data) 
    {
        $merchant_data = [];

        foreach ( $this->getData() as $k => $v ) {
            if ( !empty($v) ) {
                $merchant_data[$k] = urlencode($v);
            }
        }

        $merchant_data['redirect_url'] = $this->getReturnUrl();
        $merchant_data['cancel_url'] = $this->getCancelUrl();

        $cryto = new Crypto();

        $encrypted_data = $cryto->encrypt(http_build_query($merchant_data), $this->getWorkingKey());

        $url = $this->getEndpoint() . '&encRequest=' . $encrypted_data . '&access_code=' . $this->getAccessCode();

        return $this->response = new PurchaseResponse($this, [
            'url'           => $url,
            'endpoint'      => $this->getEndpoint(),
            'enc_request'   => $encrypted_data,
            'access_code'   => $this->getAccessCode(),
            'merchant_data' => $merchant_data
        ]);
    }

    /**
     * Get endpoint
     *
     * Returns endpoint depending on test mode
     *
     * @access protected
     * @return string
     */
    protected function getEndpoint() 
    {
        return ($this->getTestMode() ? self::EP_HOST_TEST : self::EP_HOST_LIVE);
    }
}