<?php

namespace Magegadgets\Loosediamonds\Controller\Index;

class Pricefilter extends \Magento\Framework\App\Action\Action
{ 

    public function execute()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
		$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
		$CategoryLinkRepository =  $objectManager->get('Magento\Catalog\Api\CategoryLinkRepositoryInterface');
		
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
		$Shap_array[11]='Asscher';

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
		
		//$fluro2 = array(0,2,5,7,8); 
		$page =  $this->getRequest()->getPostValue('page');
		$price1 =  $this->getRequest()->getPostValue('price1');
		$price2 =  $this->getRequest()->getPostValue('price2');
		$tablemin =  $this->getRequest()->getPostValue('tablemin');
		$tablemax =  $this->getRequest()->getPostValue('tablemax');
		$caretmin =  $this->getRequest()->getPostValue('caretmin');
		$caretmax =  $this->getRequest()->getPostValue('caretmax');
		$depthmin =  $this->getRequest()->getPostValue('depthmin');
		$depthmax =  $this->getRequest()->getPostValue('depthmax');
		
		$fluro =  $this->getRequest()->getPostValue('fluro');
		$color =  $this->getRequest()->getPostValue('color');
		$cut =  $this->getRequest()->getPostValue('cut');
		$clarity =  $this->getRequest()->getPostValue('clarity');
		$symmetry =  $this->getRequest()->getPostValue('symmetry');
		
		$certifi =  $this->getRequest()->getPostValue('certifi');
		$polishmain =  $this->getRequest()->getPostValue('polishmain');
		$shape =  $this->getRequest()->getPostValue('shape');
		
		/*$price23 =  $this->getRequest()->getPostValue();
		print_r($price23);*/
		
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
			$StonemanagerFactory = $objectManager->get('Magegadgets\Loosediamonds\Model\LoosediamondsFactory');		
			$collection = $StonemanagerFactory->create()->getCollection()->addFieldToSelect('*')
							->addFieldToFilter('price' , array('from' => $price1, 'to' => $price2))
							->addFieldToFilter('carats' , array('from' => $caretmin, 'to' => $caretmax))
							->addFieldToFilter('depth' , array('from' => $depthmin, 'to' => $depthmax))
							->addFieldToFilter('table' , array('from' => $tablemin, 'to' => $tablemax));
		
			if($fluro!=''){				
		    	$collection->addFieldToFilter('fluorescence' , array('in'=>array($fluro)));
			}
			if($color!=''){				
		    	$collection->addFieldToFilter('color' , array('in'=>array($color)));
			}
			if($cut!=''){				
		    	$collection->addFieldToFilter('cut' , array('in'=>array($cut)));
			}
			if($clarity!=''){				
		    	$collection->addFieldToFilter('clarity' , array('in'=>array($clarity)));
			}
			if($symmetry!=''){				
		    	$collection->addFieldToFilter('symmetry' , array('in'=>array($symmetry)));
			}
			if($certifi!=''){				
		    	$collection->addFieldToFilter('certificate' , array('in'=>array($certifi)));
			}
			if($polishmain!=''){				
		    	$collection->addFieldToFilter('polish' , array('in'=>array($polishmain)));
			}
			if($shape!=''){				
		    	$collection->addFieldToFilter('shape' , array('in'=>array($shape)));
			}
			$totdiamondcount = count($collection);
			//echo $totdiamondcount = count($collection); 

			$collection->setPageSize(20);
			//echo $collection->getSelect();
			//print_r($collection->getData());

			$loosD = array();
			
			$i = 0;	
			$loosD[$i]['total'] = $totdiamondcount; 
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
				
				$_product = $productRepository->get($diamondInfo['item_id']);
				$loosD[$i]['url'] = $_product->getProductUrl(); 

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




















