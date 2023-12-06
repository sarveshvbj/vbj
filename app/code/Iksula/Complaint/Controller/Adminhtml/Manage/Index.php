<?php
namespace Iksula\Complaint\Controller\Adminhtml\Manage;

class Index extends \Magento\Backend\App\Action
{
    
    protected $resultPageFactory = false;
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory
	) {
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}

	public function execute()
	{
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return  $resultPage;
	}
}