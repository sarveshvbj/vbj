<?php
namespace Magegadgets\Pricemanger\Observer;

class Pricemanger implements \Magento\Framework\Event\ObserverInterface
{
	
  protected $_resource;
  protected $_adminsession;
	
  public function __construct(
	\Magento\Framework\App\ResourceConnection $resource,
	\Magento\Backend\Model\Session $adminsession
  ){
		$this->_resource = $resource;
		$this->_adminsession = $adminsession;
  }	
	
  public function execute(\Magento\Framework\Event\Observer $observer)
  {
	 $_product = $observer->getProduct();  // you will get product object
	  
     $connection = $this->_resource->getConnection();
	 $priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");
	 
	 $metalRate = 0;
	 
	 $weight = $_product->getWeight();
	 $wastage = $_product->getWastage(); 
	 $business_wastage = $_product->getBusinessWastage();
	 $making_charge = $_product->getMakingCharge();
	 $metal = $_product->getResource()->getAttribute('metal')->getFrontend()->getValue($_product);
	 $purity =  $_product->getResource()->getAttribute('purity')->getFrontend()->getValue($_product);
	 $metalCode = floor(str_replace("%","",$purity));
	 
	 if($metal=='Gold'){
		$metalRate = $priceManagerData[$purity];
	}else if($metal=='Platinum'){
		if($metalCode==100){
			$metalRate = $priceManagerData['p_hundred'];
		}else if($metalCode==99){
			$metalRate = $priceManagerData['ninety_nine'];
		}else if($metalCode==95){
			$metalRate = $priceManagerData['ninety_five'];
		}
	}else if($metal=='Silver'){
		if($metalCode==100){
			$metalRate = $priceManagerData['hundred'];
		}else if($metalCode==92){
			$metalRate = $priceManagerData['ninety_two'];
		}else if($metalCode==91){
			$metalRate = $priceManagerData['ninety_one'];
		}
	}
	
	$wastageweight = $wastage*($weight/100);
	$wastageamnt = $wastageweight*$metalRate;
	$businesswastageweight = $business_wastage*($weight/100);
	$businesswastageamnt = $businesswastageweight*$metalRate;
	$makingcharges = $weight*$making_charge;
	$metalamnt = $weight*$metalRate;
	
	$stoneRate=0;
    $stoneBusinessRate=0;
	
	$existingStoneInfo = $_product->getStoneInformation();
	$stoneInfo = $this->_adminsession->getStoneInfo();
	if($stoneInfo){
		$stoneRate = $stoneInfo['stonePrice'];
		$stoneBusinessRate = $stoneInfo['stoneBusinessPrice'];
		$_product->setStonePrice($stoneInfo['stonePrice']);
		$_product->setStoneBusinessPrice($stoneInfo['stoneBusinessPrice']);
		$_product->setStoneInformation($stoneInfo['stoneinfo']);
		$this->_adminsession->unsStoneInfo();
	}else{
		$stoneRate = $_product->getStonePrice();
		$stoneBusinessRate = $_product->getStoneBusinessPrice();
	}
	$this->_adminsession->unsStoneInfo();
	
	//echo "$wastageamnt + $makingcharges + $metalamnt + $stoneRate";
	//echo "<br/>";
	$price = $wastageamnt + $makingcharges + $metalamnt + $stoneRate;
	$businessprice = $businesswastageamnt + $makingcharges + $metalamnt + $stoneBusinessRate;
	
	$taxamount_customer = 0;
	$taxamount_business = 0;
	
	/*======== Tax Function remove from price manager =======*/
	
	/*
	$taxamount_customer_option = $priceManagerData['tax_customer'];
	$taxamount_business_option = $priceManagerData['tax_business'];
	
	
	if($taxamount_customer_option !=0 && $taxamount_customer_option!=""){
		$taxamount_customer = $price / $taxamount_customer_option;
	}else{
		$taxamount_customer = 0;
	}
	
	if($taxamount_business_option !=0 && $taxamount_business_option!=""){
		$taxamount_business = $businessprice / $taxamount_business_option;
	}else{
		$taxamount_business = 0;
	}*/
	
	/*======== End Tax Function remove from price manager =======*/
	
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
	
	//$totalprices = $price + $taxamount_customer;
	//$totalbusiness = $businessprice + $taxamount_business;
	
	$totalprices = $price;
	$totalbusiness = $businessprice;
	//exit();
	$_product->setTaxAmount($taxamount_customer);
	$_product->setTaxAmountBusiness($taxamount_business);
	
	$attrSetId = $_product->getAttributeSetId();
	  if($attrSetId == 4)
	    {
			$_product->setPrice($totalprices);
		}
	//$_product->setPrice($totalprices);
	$_product->setBusinessPrice($totalbusiness);
	
	  
	$_product->save();
    return $this;
  }
}