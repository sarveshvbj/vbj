<?php

// To Run this script Run followig: command php -dmemory_limit=5G updatePrice.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ignore_user_abort(true);
@ini_set("output_buffering", "Off");
@ini_set('implicit_flush', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('max_execution_time',0);
@ini_set("memory_limit", "-1");

header( 'Content-type: text/html; charset=utf-8' );

use Magento\Framework\App\Bootstrap; 
require('app/bootstrap.php');	
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$productCollections = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
$Ids = array(12920,12917,12918,12919,12911);
$collection = $productCollections->create()->addAttributeToSelect('*');
$collection->addAttributeToFilter('attribute_set_id', 4);
//$collection->addAttributeToFilter('entity_id', ['in' => $Ids]);
$collection->addAttributeToSort('entity_id','desc');

$collection->setPageSize(15000);
$pages = $collection->getLastPageNumber();
$currentPage = 1;


//$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
$productFactory =  $objectManager->get('\Magento\Catalog\Model\ProductFactory');
$priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");

echo "Total Products: ". count($collection);
echo "Total Pages ".$pages;
echo PHP_EOL;

do {

	$collection->setCurPage($currentPage);
        $collection->load();
 echo "Current Page ".$currentPage;
$i=1;
foreach($collection as $products){
		//$productSku = '159G657' ; 
		//$product_id = 7227; 
		//$_product = $productRepository->get($productSku);
		if($products->getTypeId() != 'configurable') {
		

		$category_id='';
        	foreach($products->getCategoryIds() as $ids){
	        	$category_id= $ids;
        	}
	        
	       $collection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Category\CollectionFactory')
		                ->create()
		                ->addAttributeToSelect('*')
		                ->addAttributeToFilter('entity_id',['eq'=>$category_id])
		                ->addAttributeToSelect('discount',1);

		$categoryDiscount=0;
		$categoryData = $collection->getData('discount');
		
		foreach ($categoryData as $key => $value) {
			$categoryDiscount= $value['discount'];
		}

		$productSku = $products->getSku(); 
		$product_id = $products->getId(); 
		$storeId = '1';
		$_product = $productFactory->create()->setStoreId($storeId)->load($product_id);
		
		 $metalRate = 0;

		 $weight = $_product->getWeight();
		 $wastage = $_product->getWastage(); 
		 $business_wastage = $_product->getBusinessWastage();
		 $making_charge = $_product->getMakingCharge();
		 $metal = $_product->getResource()->getAttribute('metal')->getFrontend()->getValue($_product);
		
		 $metalDiscount = $_product->getResource()->getAttribute('metal_discount_in')->getFrontend()->getValue($_product);
		 $diamondDiscount = $_product->getResource()->getAttribute('discount_diamond_in')->getFrontend()->getValue($_product);
		 $makingChargeDiscount = $_product->getResource()->getAttribute('discount_making_charge_in')->getFrontend()->getValue($_product);
		  $wastageDiscount = $_product->getResource()->getAttribute('discount_wastage')->getFrontend()->getValue($_product);
	
		 $metalCode = $_product->getResource()->getAttribute('purity')->getFrontend()->getValue($_product); 
		 $metal = $_product->getResource()->getAttribute('metal')->getFrontend()->getValue($_product);

		if($metal=='Gold'){
							$metalRate = $priceManagerData[$metalCode];
							}else if($metal=='Platinum'){
								if($metalCode=="100%"){
									$metalRate = $priceManagerData['p_hundred'];
								}else if($metalCode=="99.9%"){
									$metalRate = $priceManagerData['ninety_nine'];
								}else if($metalCode=="95.0%"){
									$metalRate = $priceManagerData['ninety_five'];
								}
							}else if($metal=='Silver'){
								if($metalCode=="100%"){
									$metalRate = $priceManagerData['hundred'];
								}else if($metalCode=="92.5%"){
									$metalRate = $priceManagerData['ninety_two'];
								}else if($metalCode=="91.6%"){
									$metalRate = $priceManagerData['ninety_one'];
								}
							}
		
		$wastageweight = $wastage*($weight/100);
		$wastageamnt = $wastageweight*$metalRate;
		$businesswastageweight = $business_wastage*($weight/100);
		$businesswastageamnt = $businesswastageweight*$metalRate;
		$makingcharges = $weight*$making_charge;
		$metalamnt = $weight*$metalRate;
		
		$stoneRate = $_product->getStonePrice();
		$stoneBusinessRate = $_product->getStoneBusinessPrice();
		
		$price = $wastageamnt + $makingcharges + $metalamnt + $stoneRate;
		$businessprice = $businesswastageamnt + $makingcharges + $metalamnt + $stoneBusinessRate;

		$taxamount_customer = 0;
		$taxamount_business = 0;
		$taxClassselted = $_product->getTaxClassId();

		if($taxClassselted == 9){
			$taxamount_customer = ($price * 3)/100;
			$taxamount_business = ($businessprice * 3)/100;
		}elseif($taxClassselted == 10){
			$taxamount_customer = ($price * 0.25)/100;
			$taxamount_business = ($businessprice * 0.25)/100;
		}elseif($taxClassselted == 11){
			$taxamount_customer = ($price * 12)/100;
			$taxamount_business = ($businessprice * 12)/100;
		}elseif($taxClassselted == 12){
			$taxamount_customer = ($price * 28)/100;
			$taxamount_business = ($businessprice * 28)/100;
		}

/// ************     new calc   ******************** 
		$diamondinfo = json_decode($_product->getStoneInformation(),true);
		$taxClassselteds = $_product->getTaxClassId();
		$taxLable = '';
		if($taxClassselteds == 9){
			$taxLable = '3.00';
		}elseif($taxClassselteds == 10){
			$taxLable = '0.25';
		}elseif($taxClassselteds == 11){
			$taxLable = '12.00';
		}elseif($taxClassselteds == 12){
			$taxLable = '28.00';
		}

		$metalDiscountValue = ($metalRate*$weight)*($metalDiscount/100);
		$wastageDiscountValue = $wastageamnt*($wastageDiscount/100);
        $diamondDiscountValue = 0;
		
		if($diamondinfo !=''){
		foreach($diamondinfo as $newDiamonds):
			 $diomondPriceVal = $newDiamonds[4];

			 $diamondDiscountValue+= $diomondPriceVal;
			 //$diamondDiscountValues += $multiplicationPrice*$diamondDiscount/100 ;
		endforeach;
		}

		$diamondDiscountValue =  $diamondDiscountValue*$diamondDiscount/100;

		$makingRate = $making_charge*$weight; 
		
		$makingChargeDiscountValue = $makingChargeDiscount*$makingRate/100 ;

		$categoryDiscount = $categoryDiscount*$metalRate/100;
		



	    $grandDiscount = $wastageDiscountValue + $metalDiscountValue + $diamondDiscountValue + $makingChargeDiscountValue+$categoryDiscount;


		echo $products->getId();
		echo ":before:";
		echo $wastageDiscountValue + $metalDiscountValue + $diamondDiscountValue + $makingChargeDiscountValue;
		echo ':After:';
		echo $grandDiscount;
	
	
/// ************     new calc End ****************** 

		$totalSpecialPrice = $price - $grandDiscount;

		///$gst2 = $totalprices*$taxLable/100;	

		//$totalSpecialPrice = $totalprices ;
		$totalbusiness = $businessprice;
		//echo $taxamount_customer;
		$_product->setTaxAmount($taxamount_customer);
		$_product->setCategoryDiscount($categoryDiscount);
		$_product->setTaxAmountBusiness($taxamount_business);
		$_product->setPrice($price);
		$_product->setBusinessPrice($totalbusiness);
		$_product->setSpecialPrice($totalSpecialPrice);
		if($_product->save()){
			echo $i."Product Price Updated: [".$productSku."]";
		}else{
			echo $i."Product Update Failed: [".$productSku."]";
		} 
		//exit();
		echo PHP_EOL;
	}
		$i++;
}

$currentPage++;
        //clear collection and free memory
        $collection->clear();
}while ($currentPage <= $pages);

echo "All Products Price Updated Successfully";
echo PHP_EOL;
echo "Please Run Indexing Command: php -dmemory_limit=5G bin/magento indexer:reindex";
echo PHP_EOL;
echo PHP_EOL;
?>
