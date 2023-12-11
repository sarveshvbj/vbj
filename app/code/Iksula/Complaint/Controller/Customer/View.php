<?php 
namespace Iksula\Complaint\Controller\Customer;  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class View extends \Magento\Framework\App\Action\Action { 

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
	    public function execute()
    {
        

}
} 
?>       