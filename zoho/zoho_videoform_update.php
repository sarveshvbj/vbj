<?php

// To Run this script Run followig: command php -dmemory_limit=5G updatePrice.php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(0);
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
require 'jwt_lib/JWT.php';
require 'jwt_lib/SignatureInvalidException.php';
require 'jwt_lib/BeforeValidException.php';
require 'jwt_lib/ExpiredException.php';
require 'jwt_lib/JWK.php';
use \Firebase\JWT\JWT;
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$jwt_helper = $objectManager->get('Vaibhav\Tryathome\Helper\Data');


                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('custom_userdata');
                 
                 
                 $sql = "SELECT * FROM `zoho_video-shopping_data` where flag='Y' LIMIT 100";
                 $results = $connection->fetchAll($sql);
                
                 
                 
                   if(count($results)>0){
                     $main_arr = array();
                     foreach ($results as  $result) {
                         //echo 'tagkey  =  '.$result['tagkey'].'<br>';
                       $arrformat = array($result['event'],$result['lead_id'],array("name"=>trim($result['name']),"id"=>$result['id'],"name"=>trim($result['name']),"email"=>trim($result['email']),"lead_status"=>trim($result['lead_status']),"main_category"=>trim($result['main_category']),"product_category"=>trim($result['product_category']),"lead_country"=>trim($result['lead_country']),"phone"=>trim($result['phone']),"lead_type"=>trim($result['lead_type']),"lead_source"=>trim($result['lead_source']),"event"=>trim($result['event'])));
                         $main_arr[]=$arrformat;
                   }
                   
                   $payload = json_encode(array("payload"=>$main_arr));
                   $secret_key = 'bb58f847-d114-42d3-9497-7ca2b3ebe98f';
                   //$token = $jwt_helper->getJwtToken($payload,$secret_key);
                    $jwt = JWT::encode($payload, $secret_key,'HS256');

                   try {
                       
            $url='https://evbk.gamooga.com/bev/?c=a747fdc6-c3b4-474b-a4b7-6cafe47136bb&jwt='.$jwt;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$token_res = curl_exec($ch);
$array_val = json_decode($token_res,true);
foreach($array_val as $value) {
    
     if($value[1]){
                      $sql1= "Update zoho_video-shopping_data Set flag ='U' where lead_id = '" . $value[1] . "'";
                      $connection->query($sql1);
                      echo $value[1]." value has been updated".PHP_EOL;
                    }
}
                   } catch(Exception $ex) {
                       
                   }
                }   


                
              
?>
