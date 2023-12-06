<?php

namespace Iksula\Complaint\Controller\Adminhtml\Manage;

class Save extends \Magento\Backend\App\Action{

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{
		
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function execute(){
		
	}
}