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

 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$jwt_helper = $objectManager->get('Vaibhav\Tryathome\Helper\Data');


                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('custom_userdata');
                 
                 
                 $sql = "SELECT * FROM `zoho_videoshopping_data`";
                 $results = $connection->fetchAll($sql);
              
                
                 $curl_access1= curl_init('https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3&client_id=1000.JNJ5X1SHVCC3EAHX8J4DNAXPQ5SYYH&client_secret=cbe1963424157859fc22285e7eb01e17dd1fc4d039&grant_type=refresh_token');
            curl_setopt($curl_access1, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl_access1, CURLOPT_HEADER, 0);
            curl_setopt($curl_access1, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_access1, CURLOPT_HTTPHEADER, array("Content-Length:0"));
            curl_setopt($curl_access1, CURLOPT_RETURNTRANSFER, true);
            $jsonobj1 = curl_exec($curl_access1);
            $arr_ref1 = json_decode($jsonobj1,true);
            $reftoken = $arr_ref1['access_token'];
             
             
             
             
           
            
            
                 
                 foreach ($results as  $result) {
                     
               
                $urlget="https://www.zohoapis.com/crm/v2/Leads/".$result['lead_id']."";
                //echo "<pre>";
             //print_r($urlget);
                
                
                 // $curl = curl_init("https://www.zohoapis.com/crm/v2/Leads/'".$result['lead_id']."'");
                 $curl = curl_init();

       
            curl_setopt($curl, CURLOPT_URL, $urlget);      
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            //curl_setopt($curl, CURLOPT_POSTFIELDS,  $fields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ". $reftoken
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            
           
            
            $arr = json_decode($jsonobj,true);
//print_r($arr);
            
            if(isset($arr['data'])){
               
               
               $crdate=date_create($arr['data'][0]['Created_Time']);
               $createdate=date_format($crdate,"Y-m-d H:i:s");
               $update=date_create($arr['data'][0]['Last_Activity_Time']);
               $updateddate=date_format($update,"Y-m-d H:i:s");

                $sql1= 'Update zoho_videoshopping_data Set name = "' . $arr['data'][0]['Full_Name'] . '",email = "' . $arr['data'][0]['Email'] . '",lead_status = "' . $arr['data'][0]['Lead_Status'] . '",Main_Category = "' . $arr['data'][0]['Main_Category'] . '",Lead_country = "' . $arr['data'][0]['Lead_Country'] . '",phone = "' . $arr['data'][0]['Phone'] . '",Lead_type = "' . $arr['data'][0]['Lead_Type'] . '",Lead_source = "' . $arr['data'][0]['Lead_Source'] . '",Budget_Range = "' . $arr['data'][0]['Budget_Range'] . '",Referrer = "' . $arr['data'][0]['Referrer'] . '",created_at = "' . $createdate . '",updated_at = "' . $updateddate . '" where lead_id = "' . $arr['data'][0]['id'] . '"';
                $connection->query($sql1);
                echo $arr['data'][0]['id']." value has been updated".PHP_EOL;
                
                
                
                
            }
            
               
                     
                 }
                 exit;
                 
                  
?>
