<?php
namespace Webkul\GiftCard\Model\Total;
use Magento\Quote\Model\Quote\Address\Total as AddressTotal;
class Giftcard extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $quoteValidator = null;
    protected $request;
    protected $_backendSession;

    public function __construct(\Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Session\SessionManagerInterface $backendSession,
        \Webkul\GiftCard\Model\GiftDetailFactory $giftDetail,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->request = $request;
        $this->_backendSession = $backendSession;
        $this->_giftDetail = $giftDetail;
        $this->_date = $date;
    }
  
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $giftVoucher = $quote->getGiftVoucherCode();

        if(!empty($giftVoucher)) {
            $gvCollection = $this->_giftDetail->create()->getCollection()
                            ->addFieldToSelect("*")
                            ->addFieldToFilter("gift_code", $giftVoucher)
                            ->getFirstItem();

            $expiryDate = $gvCollection->getExpiryDate();
            $currentDate = $this->_date->date()->format('Y-m-d');

            if(!empty($expiryDate) && strtotime($currentDate) > strtotime($expiryDate)) {
                $quote->setGiftVoucherCode('');
                $quote->setGiftVoucherAmt(0);
            }
        }
        
        /*$coupon_code = $this->_backendSession->getCoupancode();
        
          
        $balance =  $quote->getGiftVoucherAmt();
        $grand_total = $quote->getBaseGrandTotal();
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
        $grandTotal = $cart->getQuote()->getGrandTotal();
        
        if(empty($coupon_code)){
            $new_amount = 0;     
        }else if($balance >= $grand_total) {
            $new_amount = -$grand_total;
        }else{
            $new_amount = $balance;     
        }

        $total->addTotalAmount('customdiscount', -$new_amount);
        $total->addBaseTotalAmount('customdiscount', -$new_amount);
        $total->setGrandTotal($quote->getGrandTotal() + $new_amount); 
        $total->setBaseGrandTotal($quote->getBaseGrandTotal() + $new_amount); */
       
        return $this;
    } 
 

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
            if($quote->getGiftVoucherAmt() > 0)
            {
                return [
                    'code' => 'giftcard',
                    'title' => 'Gift Card',
                    'value' => -$quote->getGiftVoucherAmt()
                ];
            }

    }
}