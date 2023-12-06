<?php
namespace Magegadgets\Pricemanger\Controller\Index;

use Magento\Backend\App\Action;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$option_values = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");
		unset($option_values['id']);
		echo json_encode($option_values);
		exit();
    }
}