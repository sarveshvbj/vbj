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
$jwt_helper = $objectManager->get('Vaibhav\Tryathome\Helper\Data');
$priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");

/*echo "Total Products: ". count($collection);*/
//echo PHP_EOL;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('custom_userdata');
                 $sql = "SELECT * FROM `custom_userdata` where flag='Y'";
                 $results = $connection->fetchAll($sql);
                
                 if(count($results)>0){
                     $main_arr = array();
                     foreach ($results as  $result) {
                         //echo 'tagkey  =  '.$result['tagkey'].'<br>';
                        $arrformat = array($result['group'],$result['id'],array("name"=>trim($result['name']),"id"=>$result['id'],"category"=>trim($result['category']),"mobile"=>trim($result['mobile']),"product_name"=>trim($result['product_name']),"price"=>trim($result['price']),"date_of_purchase"=>trim($result['date_of_purchase'])));
                         $main_arr[]=$arrformat;
                   }
                   
                   $payload = json_encode(array("payload"=>$main_arr));
                   $secret_key = 'bb58f847-d114-42d3-9497-7ca2b3ebe98f';
                   $token = $jwt_helper->getJwtToken($payload,$secret_key);
                   try {
                       
            $url='https://evbk.gamooga.com/bev/?c=a747fdc6-c3b4-474b-a4b7-6cafe47136bb&jwt='.$token;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$token_res = curl_exec($ch);
$array_val = json_decode($token_res,true);
foreach($array_val as $value) {
    
     if($value[1]){
                      $sql1= "Update custom_userdata Set flag ='U' where id = '" . $value[1] . "'";
                      $connection->query($sql1);
                      echo $value[1]." value has been updated".PHP_EOL;
                    }
}
                   } catch(Exception $ex) {
                       
                   }
                }   
             
                //  if($intersects){
                //  foreach($intersects as $key => $intersect){
	               //if($productSave){
                //       $sql1= "Update tagged_values Set flag ='U' where tagkey = '" . $intersect . "'";
                //       $connection->query($sql1);
                //     }
                //  }
                //  }
              
?>
