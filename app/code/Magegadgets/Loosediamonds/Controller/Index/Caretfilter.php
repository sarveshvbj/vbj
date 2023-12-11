<?php

namespace Magegadgets\Loosediamonds\Controller\Index;

class Caretfilter extends \Magento\Framework\App\Action\Action
{ 

    public function execute()
    {

		$page =  $this->getRequest()->getPostValue('page');
		$caretmin =  $this->getRequest()->getPostValue('caretmin');
		$caretmax =  $this->getRequest()->getPostValue('carettmax');

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
		$StonemanagerFactory = $objectManager->get('Magegadgets\Loosediamonds\Model\LoosediamondsFactory');		
		$collection = $StonemanagerFactory->create()->getCollection()->addFieldToSelect('*')
						->addFieldToFilter('carats' , array('from' => $caretmin, 'to' => $caretmax))->setPageSize(5)->setCurPage($page);
		//print_r($collection->getData());

		$Shap_array=array(); 
		$Shap_array[0]='Round';
		$Shap_array[1]='Cushion';
		$Shap_array[2]='Emerald';
		$Shap_array[3]='Heart';
		$Shap_array[4]='Oval';
		$Shap_array[5]='Perl';
		$Shap_array[6]='Marquise';
		$Shap_array[7]='Pear';
		$Shap_array[8]='Radiant';
		$Shap_array[9]='Triangle';
		$Shap_array[10]='Princess';

		$Color_array=array(); 
		$Color_array[0]='D';
		$Color_array[1]='E';
		$Color_array[2]='F';
		$Color_array[3]='FY';
		$Color_array[4]='G';
		$Color_array[5]='H';
		$Color_array[6]='I';
		$Color_array[7]='J';
		$Color_array[8]='K';
		$Color_array[9]='L';
		$Color_array[10]='M';
		$Color_array[11]='N';
		$Color_array[12]='Y-Z';

		$Clarity_array=array(); 
		$Clarity_array[0]='FL';
		$Clarity_array[1]='I1';
		$Clarity_array[2]='IF';
		$Clarity_array[3]='SI';
		$Clarity_array[4]='SI1';
		$Clarity_array[5]='SI2';
		$Clarity_array[6]='VS';
		$Clarity_array[7]='VS1';
		$Clarity_array[8]='VS2';
		$Clarity_array[9]='VVS1';
		$Clarity_array[10]='VVS2';

		$Global_array=array(); 
		$Global_array[0]='Excellent';
		$Global_array[1]='Good';
		$Global_array[2]='Very Good';

		$Fluro_array=array(); 
		$Fluro_array[0]='None';
		$Fluro_array[1]='Excellent';
		$Fluro_array[2]='Faint';
		$Fluro_array[3]='Fluorescence';
		$Fluro_array[4]='Good';
		$Fluro_array[5]='Medium';
		$Fluro_array[6]='Slight';
		$Fluro_array[7]='Strong';
		$Fluro_array[8]='Very Good';
		$Fluro_array[9]='Very Slight';
		
		
		$loosD = array();
		$i = 0;	
		foreach($collection->getData() as $diamondInfo ){
			$loosD[$i]['price'] = $diamondInfo['price'];
			$loosD[$i]['measurement'] = $diamondInfo['measurement'];
			$loosD[$i]['certificate_no'] = $diamondInfo['certificate_no'];
			$loosD[$i]['item_id'] = $diamondInfo['item_id'];
			$loosD[$i]['depth'] = $diamondInfo['depth'];
			$loosD[$i]['table'] = $diamondInfo['table'];
			$loosD[$i]['carats'] = $diamondInfo['carats'];
			$loosD[$i]['certificate_link'] = $diamondInfo['certificate_link'];
			$loosD[$i]['certificate'] = $diamondInfo['certificate'];
			
			$shape = $diamondInfo['shape']; 
			$loosD[$i]['shape'] = $Shap_array[$shape];
			$color = $diamondInfo['color']; 
			$loosD[$i]['color'] = $Color_array[$color];
			$clarity = $diamondInfo['clarity']; 
			$loosD[$i]['clarity'] = $Clarity_array[$clarity];
			$cut = $diamondInfo['cut']; 
			$loosD[$i]['cut'] = $Global_array[$cut];
			$polish = $diamondInfo['polish']; 
			$loosD[$i]['polish'] = $Global_array[$polish];
			$symmetry = $diamondInfo['symmetry']; 
			$loosD[$i]['symmetry'] = $Global_array[$symmetry];
			$fluorescence = $diamondInfo['fluorescence']; 
			$loosD[$i]['fluorescence'] = $Fluro_array[$fluorescence];
			
			$i++; 
		}
		echo json_encode($loosD);
    }
}








