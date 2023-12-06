<?php
namespace Magegadgets\Pricemanger\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
	
    public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		$resultRedirect = $this->resultRedirectFactory->create();
		
        if ($data) {
			unset($data['form_key']);
			/*print "<pre>";
			print_r($data);*/
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			
			$K24 = $data['24K'];
			$K22 = $data['22K'];
			$K18 = $data['18K'];
    		$K14 = $data['14K'];
    		$hundred = $data['hundred'];
    		$ninety_two = $data['ninety-two'];
    		$ninety_one = $data['ninety-one'];
    		$p_hundred = $data['p-hundred'];
    		$ninety_nine = $data['ninety-nine'];
    		$ninety_five = $data['ninety-five'];
    		$usd = $data['usd'];
    		$tax_customer = $data['tax-customer'];
    		$tax_business = $data['tax-business'];
			
			$UpdateSql = "UPDATE `pricemanager` SET `24K` = '$K24' , `22K` = '$K22' , `18K` = '$K18' , `14K` = '$K14' , `hundred` = '$hundred' , `ninety_two` = '$ninety_two' , `ninety_one` = $ninety_one , `p_hundred` = $p_hundred , `ninety_nine` = $ninety_nine , `ninety_five` = $ninety_five , `usd` = $usd , `tax_customer` = $tax_customer , `tax_business` = $tax_business  WHERE `pricemanager`.`id` = 1;";  
			
            $resp = $connection->query($UpdateSql);  
			
			/* $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$productCollections = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
			$collection = $productCollections->create()->addAttributeToSelect('*')->load()->addAttributeToFilter('attribute_set_id', array('like' => '4'));
			$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
			
			$priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");
			
			foreach($collection as $products){
				$productSku = $products->getSku(); 
				$_product = $productRepository->get($productSku);
			
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
	

			$totalprices = $price + $taxamount_customer;
			$totalbusiness = $businessprice + $taxamount_business;

			$_product->setTaxAmount($taxamount_customer);
			$_product->setTaxAmountBusiness($taxamount_business);
			$_product->setPrice($totalprices);
			$_product->setBusinessPrice($totalbusiness);
			$_product->save(); 
			}
			//exit();
			*/
			
			$this->messageManager->addSuccess(__('Price was successfully updated.'));
			return $resultRedirect->setPath('*/*/index');
		}
	}
}

?>