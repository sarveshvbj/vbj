<?php
declare(strict_types=1);

namespace Ifidapp\Flyeradmin\Cron;

class updatePrice{

    /*Product collection variable*/ 
    protected $_productCollection;


    public function __construct(      
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,        
        array $data = []
    )
    {    
        $this->_productCollection= $productCollection;    
     
    }
    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/updatePrice.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('updatePrice Started ...');
        $collection = $this->_productCollection->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('attribute_set_id', 4);
        $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addCategoriesFilter(['in' => 140]);
        $collection->addAttributeToSort('entity_id','desc');
        $collection->setPageSize(100);
        $collection->setCurPage(1);
        $pages = $collection->getLastPageNumber();
        $currentPage = 1;
        $collection->load();
        $logger->info('Total No of Products - '. count($collection));
        $logger->info('Total No of Pages - '. $pages);
        $productFactory =  $objectManager->get('\Magento\Catalog\Model\ProductFactory');
        $priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1 AND `is_updated`=0");
        foreach($collection as $products){
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
        if(null !== $_product->getStonePrice()){
            $stoneRate = (int)$_product->getStonePrice();
        }else{
            $stoneRate = 0.00;
        }
        if(null !== $_product->getStoneBusinessPrice()){
            $stoneBusinessRate = $_product->getStoneBusinessPrice();
        }else{
            $stoneBusinessRate = 0.00;
        }
        
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
        $diamondinfo = '';
        if($_product->getStoneInformation()){
            $diamondinfo = json_decode($_product->getStoneInformation(),true)?: []; 
        }
        
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
            if(isset($newDiamonds[4])){
                $diomondPriceVal = $newDiamonds[4];
            }
            $diamondDiscountValue+= $diomondPriceVal;
             //$diamondDiscountValues += $multiplicationPrice*$diamondDiscount/100 ;
        endforeach;
        }

        $diamondDiscountValue =  $diamondDiscountValue*$diamondDiscount/100;

        $makingRate = $making_charge*$weight; 
        
        $makingChargeDiscountValue = $makingChargeDiscount*$makingRate/100 ;

        $categoryDiscount = $categoryDiscount*$metalRate/100;
        
        $grandDiscount = $wastageDiscountValue + $metalDiscountValue + $diamondDiscountValue + $makingChargeDiscountValue+$categoryDiscount;


        /*echo $products->getId();
        echo ":before:";
        echo $wastageDiscountValue + $metalDiscountValue + $diamondDiscountValue + $makingChargeDiscountValue;
        echo ':After:';
        echo $grandDiscount;*/
    
    
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
            //echo $i."Product Price Updated: [".$productSku."]";
            $logger->info('Price Updated for Products - '. print_r($productSku, true));
            $UpdateSql = "UPDATE `pricemanager` SET `is_updated` = '1' WHERE `pricemanager`.`id` = 1;";  
            $resp = $connection->query($UpdateSql);
        }else{
            //echo $i."Product Update Failed: [".$productSku."]";
            $logger->info('Price Updated for Products - '. print_r($productSku, true));
        } 
        //exit();
        echo PHP_EOL;
    }
       // $i++;
}
        }


    }
?>