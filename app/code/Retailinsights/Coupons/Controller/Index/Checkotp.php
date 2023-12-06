<?php
 
namespace Retailinsights\Coupons\Controller\Index;
 
use Magento\Backend\App\Action\Context;
 
class Checkotp extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
   
    public function __construct(
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        Context $context
    )
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        echo "this";
        $orderIds = $this->getRequest()->getPost('orderIds');
        // $orderIds= array_map('trim', $orderIds);
        // $orderIds = array_unique($orderIds);

        // foreach($orderIds as $IncrementId){
        //     $result[$IncrementId] = $this->getOrderStatus(trim($IncrementId));
        // }

        // $resultJson = $this->resultJsonFactory->create();
        // $resultJson->setData($result);


        return 'yes';
        return $resultJson;
    }

 
}
