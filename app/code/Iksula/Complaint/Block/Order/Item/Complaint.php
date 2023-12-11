<?php
namespace Iksula\Complaint\Block\Order\Item;

// class Complaint extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
class Complaint extends \Magento\Framework\View\Element\Template
{

    // protected $item;

    // public function setItem($item = null)
    // {
    // 	return $this->setItem($item);
    // }

    protected $item;
    protected $order;
    protected $customerSession;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Sales\Model\Order\Item $item, \Magento\Customer\Model\Session $customerSession, \Magento\Sales\Api\Data\OrderInterface $order,
    array $data = array()
        ) {
        $this->customerSession = $customerSession;
        $this->item = $item;
        $this->order = $order;
       parent::__construct($context, $data);
    }

    public function _prepareLayout(){
        $this->setItemId($this->getRequest()->getParam('item_id'));
    }

    public function getItem($item_id){
        return $this->item->load($item_id);
    }

    public function getOrder($orderId){
        return $this->order->load($orderId);
    }
    
    public function getComplaintBaseUrl(){
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    public function getCustomerId(){
        return $this->customerSession->getCustomer()->getId();
    }
}