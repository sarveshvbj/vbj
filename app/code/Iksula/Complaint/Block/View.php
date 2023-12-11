<?php
namespace Iksula\Complaint\Block;

class View extends \Magento\Framework\View\Element\Template{

  protected $_collection;
  

  public function __construct(
  	\Magento\Framework\View\Element\Template\Context $context
    ) {
   parent::__construct($context);
 }

 protected function _prepareLayout()
 {
  //$this->setComplaintId($this->getRequest()->getParam('complaint_id'));
}




}