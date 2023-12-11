<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\GiftCard\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
 
class AfterPlaceOrder implements ObserverInterface
{
    /** @var \Magento\Framework\Logger\Monolog */
    protected $_logger;

    /** @var Magento\Framework\App\RequestInterface */
    protected $_request;

    /**
     * @param \Psr\Log\LoggerInterface               $loggerInterface
     * @param RequestInterface                       $requestInterface
     */
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        RequestInterface $requestInterface,
        \Magento\Sales\Model\Order $salesOrder,
        \Webkul\GiftCard\Model\GiftUserFactory $giftUserFactory,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\Rule $salesRule
    ) {
        $this->_logger = $loggerInterface;
        $this->_request = $requestInterface;
        $this->_salesOrder = $salesOrder;
        $this->_giftUserFactory = $giftUserFactory;
        $this->_session = $session;
        $this->_customerSession = $customerSession;
        $this->_salesRule = $salesRule;
    }

    /**
     * This is the method that fires when the event runs.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $oids=$observer->getOrderIds();
        $sl = $this->_salesOrder->load($oids);
            $rpr = $this->_session->getReducedprice();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderObject = $objectManager->get('\Magento\Sales\Model\Order');
        $order = $orderObject->load($oids);
        $items = $order->getAllVisibleItems();
        foreach ($items as $item) {
    $options = $item->getProductOptions();        
    if (isset($options['options']) && !empty($options['options'])) {        
        foreach ($options['options'] as $option) {
            echo 'Title: ' . $option['label'] . '<br />';
            echo 'ID: ' . $option['option_id'] . '<br />';
            echo 'Type: ' . $option['option_type'] . '<br />';
            echo 'Value: ' . $option['option_value'] . '<br />' . '<br />';
        }
    }
}
exit;
        if (!empty($rpr)) {
            $gift_user_data=[];
            $customerEmail=$this->_customerSession->getCustomer()->getEmail();
            $customerName=$this->_customerSession->getCustomer()->getName();
            $gift_user_data["orderId"]=$sl->getIncrementId();
            $gift_user_data["reciever_email"]=$customerEmail;
            $gift_user_data["reciever_name"]=$customerName;
            $gift_user_data["reduced_ammount"]=$this->_session->getReducedprice();
            $model3=$this->_giftUserFactory->create()
            ->getCollection()
            ->addFieldToFilter("main_table.code", $this->_session->getCoupancode());
            // ->addFieldToFilter("main_table.email", $customerEmail);
            foreach ($model3 as $m3) {
                $amnt=$m3->getRemainingAmt();
                $m3->setRemainingAmt($amnt-$this->_session->getReducedprice())->save();
            }
            $collection = $this->_salesRule->getCollection()->load();
            foreach ($collection as $m) {
                if ($m->getName() == $this->_session->getCoupancode()) {
                    $m->delete();
                }
            }
            $coupon_model = $this->_salesRule->getCollection()->load();
            foreach ($coupon_model as $cpn) {
                if (trim($cpn->getName()) == trim($this->_session->getCoupancode())) {
                    $cpn->delete();
                }
            }
            $this->_session->setReducedprice(null);
            $this->_session->setCoupancode(null);
        }
    }
}
