<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vaibhav\Sortby\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Paypal\Model\Payflow\Transparent;
use Magento\Quote\Api\Data\PaymentInterface;

class SavePlaceInfoToOrderObserver extends AbstractDataAssignObserver
{
    /**
     * @var array
     */
    protected $_request;
    protected $_cookieManager;
    protected $_objectManager;
    public function __construct(
    \Magento\Framework\App\RequestInterface $request,
    \Psr\Log\LoggerInterface $logger,
    \Magento\Sales\Model\Order\Status\HistoryFactory $historyFactory,
    \Magento\Sales\Model\OrderFactory $orderFactory,
    \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
    \Magento\Framework\ObjectManagerInterface $objectmanager
) { 
    $this->_request = $request;
    $this->_logger = $logger;
    $this->_historyFactory = $historyFactory;
    $this->_orderFactory = $orderFactory;
    $this->_cookieManager = $cookieManager;
    $this->_objectManager = $objectmanager;
}

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /*$cookieValue = $this->_cookieManager->getCookie(\Iksula\Complaint\Controller\Customer\Index::cookie_name);
        echo($cookieValue);*/
        /*if( isset( $_COOKIE['cookie_name'])) 
        { 
             echo $store = $_COOKIE['cookie_name']; 
        } 
        else 
        { 
             echo 'no cookie for you!'; 
        }*/
        /*echo '<pre>';
      /*  print_r($_COOKIE);*/
       /*$paymentOrder = $observer->getEvent()->getPayment();
       $order = $paymentOrder->getOrder();
       print_r($order->getData());
       //$obj->setStore($store);
         if( isset( $_COOKIE['cookie_name'])) 
        { 
             $store = $_COOKIE['cookie_name']; 
        } 
        else 
        { 
             echo 'no cookie for you!'; 
        }
        exit;*/
        
    }

    /**
     * @param array $ccData
     * @return array
     */
    /*private function sortCcData(array $ccData)
    {
        $r = [];
        foreach ($this->ccKeys as $key) {
            $r[$key] = isset($ccData[$key]) ? $ccData[$key] : null;
        }

        return $r;
    }*/
}
