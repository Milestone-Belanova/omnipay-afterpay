<?php

namespace Omnipay\AfterPay\Message;

class PurchaseResponse extends Response
{
    /**
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->isResponseHasError())
        {
            return false;
        }

        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data['redirectCheckoutUrl'];
    }
}
