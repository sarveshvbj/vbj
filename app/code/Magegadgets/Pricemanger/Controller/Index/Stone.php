<?php
namespace Magegadgets\Pricemanger\Controller\Index;

use Magento\Backend\App\Action;

class Stone extends \Magento\Framework\App\Action\Action
{
	
    public function execute()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$StonemanagerFactory = $objectManager->get('Magegadgets\Stonemanager\Model\StonemanagerFactory');
		
		$collection = $StonemanagerFactory->create()->getCollection()->addFieldToSelect('*');
		echo json_encode($collection->getData());
		exit();
		
    }
}