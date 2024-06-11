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
//        echo '<pre>'; print_r($this->data['url']); echo '</pre>'; die;

        return $this->data['url'] ?? '';
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }
}