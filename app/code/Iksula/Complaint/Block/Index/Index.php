<?php

namespace Iksula\Complaint\Block\Index;


class Index extends \Magento\Framework\View\Element\Template {
    
    protected $_checkoutSession;

    public function __construct(\Magento\Catalog\Block\Product\Context $context,
    \Magento\Checkout\Model\Session $checkoutSession,
    array $data = []
    ) {
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $data);

    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    public function CurrentOrderId(){
    	$order = $this->_checkoutSession->getLastRealOrder();
        //$orderId=$order->getEntityId();
        $orderId = $order->getIncrementId();
        return $orderId;
    }

}