<?php
namespace Vaibhav\Tryathome\Block;

class Mobile extends \Magento\Framework\View\Element\Template{
    
    protected $_collection;
    
    protected $customerSession;
    
   public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Magento\Customer\Model\Session $customerSession, array $data = array()) {
       
       $this->customerSession = $customerSession;
       parent::__construct($context, $data);
   }

  

   protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
   

   public function getCustomerId(){
        return $this->customerSession->getCustomer()->getId();
    }
   
}