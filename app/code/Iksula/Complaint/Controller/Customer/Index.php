<?php 
namespace Iksula\Complaint\Controller\Customer;  
class Index extends \Magento\Framework\App\Action\Action { 

	protected $customerSession;

	public function __construct(
		\Magento\Framework\App\Action\Context $context, 
		\Magento\Customer\Model\Session $customerSession, 
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
		) {
		$this->resultPageFactory = $resultPageFactory;
		$this->customerSession = $customerSession;
		$this->_productCollection= $productCollection; 
		parent::__construct($context);
	}
	public function execute() { 
		 
		if($this->customerSession->getCustomer()->getId()){
			$resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('customer/account');
		}
		else{
			$resultRedirect = $this->resultRedirectFactory->create();
			return $resultRedirect->setPath('customer/account/login');
		}
	}
	} 

?>       