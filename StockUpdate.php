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
$state->setAreaCode('frontend');
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$productCollections = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

$collection = $productCollections->create()->addAttributeToSelect('*');
$collection->addAttributeToFilter('attribute_set_id', 4);


//$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
$productFactory =  $objectManager->get('\Magento\Catalog\Model\ProductFactory');
$priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");

/*echo "Total Products: ". count($collection);*/
//echo PHP_EOL;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('taged_values');
                 $sql = "SELECT * FROM `tagged_values` where flag='Y'";
                 $results = $connection->fetchAll($sql);
                 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
                        $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                        /** Apply filters here */
                        $collection = $productCollection->addAttributeToSelect('*')
                                    ->load();
                        $c = array();
                        foreach ($collection as $product){
                             /*echo 'Sku  =  '.$product->getSku().'<br>';*/
                             
                             $c[]= $product->getSku();
                        }
                 if(count($results)>0){
                     $d = array();
                     foreach ($results as  $result) {
                         //echo 'tagkey  =  '.$result['tagkey'].'<br>';
	                   $productSku = $result['tagkey'];
	                   $qty = $result['pieces'];
	                   $d[]= $result['tagkey'];
                   }
                }   
                $intersects = array_intersect($c, $d);
                /*echo '<pre>', print_r($intersects), '</pre>';*/
                if($intersects){
                foreach($intersects as $key => $intersect){
                    /*echo 'intersect  =  '.$intersect.'<br>';*/
                $stockItems = $objectManager->get('Magento\CatalogInventory\Model\StockRegistry');
	                   $stockItem = $stockItems->getStockItemBySku($intersect);
	                   $stockItem->setQty(31);
	                   $stockItem->setData('is_in_stock',1);
	                   $stockItem->save();
	                   $product = $objectManager->create('\Magento\Catalog\Model\Product');
                       $value = 30;
                    $prod = $product->loadByAttribute('sku', $intersect);
                    $prod->setExpectedDeliveryDate($value);
                    $prod->setSmartPercentage(10);// name of your custom attribute
                    $productSave = $prod->save();
	              if($productSave){
                     $sql1= "Update tagged_values Set flag ='U' where tagkey = '" . $intersect . "'";
                     $connection->query($sql1);
                   }
                }
                }
                 /*$username = "vaibhav";
                     $password = "vaibhav";
                     $numbers = "7715878743"; // mobile number 7639567324  8099639614  33359 Dheeraj Sena 
                     $from = urlencode('STRIKR'); // assigned Sender_ID
                     $sender = urlencode('VAIBAV');//"vaibav";
                     /*echo $message = 'Dear Dheeraj Sena, we have received your order no 33359 Circle of Life Rose Gold Pendant, Wish
                    you Gold Luck';//'your message should be go from here'; // Message text required to deliver on mobile number
                     $message = urlencode('Inventory Updated once per hour');
                     $data =
                    "username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1";
                    //echo $data;
                     $api_url = "https://www.smsstriker.com/API/sms.php?".$data;
                     //echo $api_url;
                     $ch = curl_init();
                     curl_setopt($ch,CURLOPT_URL, $api_url);
                     curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
                     $response = curl_exec($ch);*/

/*echo "All Products Price Updated Successfully";
echo PHP_EOL;
echo "Please Run Indexing Command: php -dmemory_limit=5G bin/magento indexer:reindex";
echo PHP_EOL;
echo PHP_EOL;*/
?>
