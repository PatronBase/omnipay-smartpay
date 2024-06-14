<?php

namespace Omnipay\SmartPay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Bank Muscat Redirect Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface {
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getRedirectMethod() === 'POST' ? $this->data['endpoint'] : $this->data['url'];
    }

    public function getRedirectData()
    {
        return [
            'encRequest' => $this->data['enc_request'],
            'access_code' => $this->data['access_code']
        ];
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }
}