<?php

namespace One97\Paytm\Controller\Standard;

class Redirect extends \One97\Paytm\Controller\Paytm
{
    public function execute()
    {
       /* echo "Sarvesh Tiwari";
        echo $this->getPaytmHelper()->getUrl('checkout').'<br>';
        $order = $this->getOrder();
        echo $this->getPaytmModel()->buildPaytmRequest($order);
        //echo $returnUrl = $this->getPaytmHelper()->getUrl('checkout/onepage/success');
        echo '<pre>';
        echo $name = $this->getRequest()->getPostValue("payment[method]");
        //print_r($order->getData());
        
        exit;*/
        $promo='';
        if(isset($_GET['promo'])){
            if(trim($_GET['promo'])!=''){
                $promo=$_GET['promo'];
            }
        }
        $order = $this->getOrder();
        if ($order->getBillingAddress())
        {
            $order->setState("pending_payment")->setStatus("pending_payment");
            //$order->addStatusToHistory($order->getStatus(), "Customer was redirected to paytm.");
            $order->save();
            
            if($promo!=''){
                $order->paytmPromoCode=$promo;
            }
           $this->getResponse()->setRedirect(
                $this->getPaytmHelper()->getUrl('checkout/onepage/success')
            );
            //$this->getPaytmHelper()->getUrl('checkout/onepage/success');
        }
        else
        {
            $this->_cancelPayment();
            $this->_paytmSession->restoreQuote();
            $this->getResponse()->setRedirect(
                $this->getPaytmHelper()->getUrl('checkout')
            );
        }
    }
}