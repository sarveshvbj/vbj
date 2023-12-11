<?php

namespace Magegadgets\Stonemanager\Controller\Adminhtml\stonemanager;

use Magento\Backend\App\Action;


class Productupdate extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		$id = $this->getRequest()->getParam('id');
		$this->messageManager->addSuccess(__('Related all Products price has been Updated'));
		
		$resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
        /*$model = $this->_objectManager->create('Magegadgets\Stonemanager\Model\Stonemanager');
		$model->load($id);*/
		
        
		
        
    }
}