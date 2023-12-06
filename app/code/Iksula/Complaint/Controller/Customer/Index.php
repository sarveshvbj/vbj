<?php 
namespace Iksula\Complaint\Controller\Customer;  
class Index extends \Magento\Framework\App\Action\Action { 

	protected $customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\View\Result\PageFactory $resultPageFactory
		) {
		$this->resultPageFactory = $resultPageFactory;
		$this->customerSession = $customerSession;
		parent::__construct($context);
	}
	public function execute() { 
		if($this->customerSession->getCustomer()->getId()){
			$this->_view->loadLayout(); 
			$this->_view->renderLayout(); 
		}
		else{
			$resultRedirect = $this->resultRedirectFactory->create();
			return $resultRedirect->setPath('customer/account/login');
		}
	} 

} 
?>       