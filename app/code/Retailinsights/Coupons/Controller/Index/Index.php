<?php
namespace Retailinsights\Coupons\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
class Index extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $resultFactory;

	public function __construct(
	    \Magento\Framework\View\Result\PageFactory $resultFactory,
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->_pageFactory = $pageFactory;
		$this->resultFactory = $resultFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
		$couponCode = $this->getRequest()->getPost('param');
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product_discamt = 0;
        $objrules = $objectManager->create('Magento\SalesRule\Model\RuleFactory')->create();
        // $rules = $objrules->getCollection();
        $rules = $objrules->getCollection()
        ->addFieldToFilter('code', $couponCode);
        
        $phone_number='';
        foreach ($rules as $tmprule) {
            $phone_number = $tmprule['phone_number'];
        }
         
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($phone_number);
        return $resultJson;

        
	}
}