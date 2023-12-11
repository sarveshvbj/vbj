<?php
namespace Magegadgets\Pricemanger\Controller\Index;

use Magento\Backend\App\Action;

class Stonesave extends \Magento\Framework\App\Action\Action
{
	
    public function execute()
    {
		$params = $this->getRequest()->getParams();
		//print_r($params);
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$AdminSession = $objectManager->get('Magento\Backend\Model\Session');
		$AdminSession->setStoneInfo($params['stoneinfo']);
		//echo $AdminSession->getStoneInfo();
    }
}