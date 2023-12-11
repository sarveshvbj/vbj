<?php
namespace Magegadgets\Stonemanager\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
 
class Data extends AbstractHelper
{
       public function Stonename($newDiamonds)
       {
		    $itemKey = $newDiamonds[0];
		    $rang = $newDiamonds[2];
		   
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
			$StonemanagerFactory = $objectManager->get('Magegadgets\Stonemanager\Model\StonemanagerFactory');		
			$collection = $StonemanagerFactory->create()->getCollection()->addFieldToSelect('name');
		    $collection->addFieldToFilter('itemkey',  $itemKey);
		    $collection->addFieldToFilter('startcent', array('lteq' => $rang));
		    $collection->addFieldToFilter('endcent', array('gteq' => $rang));
		    $stonData = $collection->getFirstItem();
		    return $stonData->getName();
		    //echo "This is Helper Stone";
       }
}