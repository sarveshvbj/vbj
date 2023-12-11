<?php

namespace Webkul\GiftCard\Controller\Adminhtml\GiftUser;

class Bulkupload extends \Magento\Backend\App\Action{

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute(){
		return $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
	}
}