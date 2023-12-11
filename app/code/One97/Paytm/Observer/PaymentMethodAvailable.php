<?php
namespace One97\Paytm\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\State;
 
class PaymentMethodAvailable implements ObserverInterface
{
 
 protected $state;
 
 public function __construct (
 State $state
 ) {
 $this->state = $state;
 }
 
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // you can replace "checkmo" with your required payment method code
        if($observer->getEvent()->getMethodInstance()->getCode()=="paytm"){
 if($this->state->getAreaCode()!="frontend"){
 $checkResult = $observer->getEvent()->getResult();
 $checkResult->setData('is_available', false); 
 }
        }
    }
}
?>