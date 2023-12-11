<?php
namespace Magegadgets\Pricemanger\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Stonesave extends \Magento\Framework\App\Action\Action
{
	
    public function execute()
    {
		$params = $this->getRequest()->getParams();
		unset($params['isAjax']);
		unset($params['form_key']);
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$AdminSession = $objectManager->get('Magento\Backend\Model\Session');
		$AdminSession->setStoneInfo($params);
		//echo $AdminSession->getStoneInfo();
    }
}