<?php

namespace Vaibhav\GoldRate\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
use Vaibhav\GoldRate\Model\ResourceModel\Goldrate\CollectionFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Result extends Action
{
    protected $resultPageFactory;
    public $collection;
    private $resultJsonFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory, CollectionFactory $collectionFactory,JsonFactory $resultJsonFactory,)
    {
        $this->collection = $collectionFactory;
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        $post_data = $this->getRequest()->getParam('selectCity');
        if($post_data){
            $collection = $this->collection->create()->addFieldToFilter('city',$post_data)->getData();
            try{
            $resultJson = $resultJson->setData(['data' => $collection]); 
            }
            catch(\Exception $e) {
                $message = $e->getMessage();
            }
            
        }
        
        return $resultJson;
    }
}