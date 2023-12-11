<?php 
date_default_timezone_set("Asia/Calcutta"); 
ob_start();
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
ini_set('memory_limit', '1024M');
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$resource = $objectManager->create('Magento\Framework\App\ResourceConnection');

function _getResourceConnection()
{
    global $objectManager;
    return $objectManager->create('Magento\Framework\App\ResourceConnection');
}

function _getReadConnection()
{
    return _getConnection('core_read');
}

function _getWriteConnection()
{
    return _getConnection('core_write');
}

function _getConnection($type = 'core_read')
{
    return _getResourceConnection()->getConnection($type);
}

function _getTableName($tableName)
{
    return _getResourceConnection()->getTableName($tableName);
}

function _getAttributeId($attributeCode)
{
    $connection = _getReadConnection();
    $sql = "SELECT attribute_id FROM " . _getTableName('eav_attribute') . " WHERE entity_type_id = ? AND attribute_code = ?";
    return $connection->fetchOne(
        $sql,
        [
            _getEntityTypeId('customer'),
            $attributeCode
        ]
    );
}

function _getEntityTypeId($entityTypeCode)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT entity_type_id FROM " . _getTableName('eav_entity_type') . " WHERE entity_type_code = ?";
    return $connection->fetchOne(
        $sql,
        [
            $entityTypeCode
        ]
    );
}

function _getLatestZohoToken() {
     $connection = _getConnection('core_read');
     $rowid=4;
      $sql = "SELECT access_token FROM " . _getTableName('zoho_api') . " WHERE id = ? ";
    return $connection->fetchOne(
        $sql,
        [
            $rowid
        ]
    );
   
}
function _setLatestZohoToken($token) {
      $rowid=4;
     $connection   = _getWriteConnection();

    $sql1 = "INSERT INTO " . _getTableName('zoho_api') . " (id,access_token) VALUES (?, ?) ON DUPLICATE KEY UPDATE access_token=VALUES(access_token)";
    $connection->query(
        $sql1,
        [
            $rowid,
            $token
        ]
    );
   
}
function _updateDataStatus($name,$email,$phone,$sku,$status) {
     $connection   = _getWriteConnection();
    $lastPassTime = date_create()->format('Y-m-d H:i:s');
     $sql2 = "UPDATE " . _getTableName('zoho_abondoned_cart') ." SET status= ?, last_pass_time=? WHERE mobile = ? and email = ? ";
    $connection->query(
        $sql2,
        [
            $status,
            $lastPassTime,
            $phone,
            $email 
        ]
    );
   
}

function _zoho_lead_send($fields) {
    $status = "";
         if(!empty($fields)) {
           $fields = json_encode($fields);
            $curTime=date('Y-m-d H:i:s', time());
$datetime = new \DateTime($curTime);
$currenDateTime = $datetime->format(\DateTime::ATOM);
       $latest_token = _getLatestZohoToken();
          if(!empty($latest_token)) {
           $status = _sendZohoApi($fields,$latest_token);
           if($status != 'success') {
            $refreshtoken = _refZohoToken();
            _setLatestZohoToken($refreshtoken);
            $status = _sendZohoApi($fields,$refreshtoken);
           } else {
            $status = "success";
           }
          }
      }
      return $status;
}

function _sendZohoApi($fields,$reftoken) {
    $curl = curl_init('https://www.zohoapis.com/crm/v2/Leads');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $arr = json_decode($jsonobj,true);
            if(isset($arr['status'])) {
            $status = $arr['status'];
            } else {
                $status = $arr['data'][0]['code'];
                $lead_id=$arr['data'][0]['details']['id'];
                if($status=="DUPLICATE_DATA") {
                     $update_status="";
                     $updatedfields = _getUpdateFields($fields,$lead_id);
                     $status = _updateZohoApi($updatedfields,$reftoken);
                      $status = _newZohoTask($reftoken,$lead_id);
                } else {
                    $status='success';
                } 
            }
           
            curl_close($curl);
            return $status;
    }
function _refZohoToken() {

    $curl_access= curl_init('https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3&client_id=1000.JNJ5X1SHVCC3EAHX8J4DNAXPQ5SYYH&client_secret=cbe1963424157859fc22285e7eb01e17dd1fc4d039&grant_type=refresh_token');
            curl_setopt($curl_access, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl_access, CURLOPT_HEADER, 0);
            curl_setopt($curl_access, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_access, CURLOPT_HTTPHEADER, array("Content-Length:0"));
            curl_setopt($curl_access, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl_access);
            $arr_ref = json_decode($jsonobj,true);
            $reftoken = $arr_ref['access_token'];
            curl_close($curl_access);
            return $reftoken;   
    }

       function _getUpdateFields($fields,$lead_id) {
        $arr_convert = json_decode($fields,true);
        $new_fields = json_encode(array("data" => array(["id" => $lead_id,"Email" => $arr_convert['data'][0]['Email'],"Lead_Status" => $arr_convert['data'][0]['Lead_Status'],"Lead_Source" => $arr_convert['data'][0]['Lead_Source'],"Remarks" => $arr_convert['data'][0]['Remarks'],"Last_Name" => $arr_convert['data'][0]['Last_Name'],"Phone" => $arr_convert['data'][0]['Phone']]))); 
       return $new_fields;
    }

     function _updateZohoApi($updatedfields,$reftoken) {

   $curl = curl_init('https://www.zohoapis.com/crm/v2/Leads');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $updatedfields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken,
                    "Content-Length:".strlen($updatedfields)
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $arr = json_decode($jsonobj,true);
            $status = $arr['data'][0]['status'];
            curl_close($curl); 
            return 'success';
    }

     function _newZohoTask($reftoken,$lead_id) {
    $curDate=date('Y-m-d');
    $Who_Id = '3764794000000356001';
    $mode='$se_module';
    $fields = json_encode(array("data" => array(["Subject" => "Call","Due_Date" => $curDate,"Status" => "Deferred","Owner" => $Who_Id,"What_Id" => $lead_id,$mode => "Leads"]))); 

   $curl = curl_init('https://www.zohoapis.com/crm/v2/Tasks');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS,  $fields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $arr = json_decode($jsonobj,true);
            print_r($arr);
            $status = $arr['data'][0]['status'];
            curl_close($curl); 
            return $status;

    }

$updatedAtFrom = date('d-m-Y H:i:s'); //Add current time
$updatedAtTo   = date("Y-m-d H:i:s", strtotime("+90 minutes"));  // $updatedAtFrom  + 90 minutes ,add 90 minutes in current time to abondened cart

        $connection =_getReadConnection();
      $update = 'update';
      $new = 'new';
      $sql = "SELECT * FROM " . _getTableName('zoho_abondoned_cart') . " WHERE status= ? OR status = ? ";
    $quote_collection = $connection->fetchAll(
        $sql,
        [
            $update,
            $new
        ]
    );
       $array = array();
                    //$fields = json_encode(array("data" => ));
       $i=0;
        foreach ($quote_collection as $quote) {

           if($i < 2) {
             $name = $quote['name'];
             $email = $quote['email'];
             $phone = $quote['mobile'];
             $sku = $quote['sku'];
             
            $fields = array("data" => array(["Last_Name" => $name,"Remarks" => $sku,"Lead_Status" => "new","Lead_Source" => "Abandoned cart","Email" => $email,"Phone" => $phone]));
            $status = _zoho_lead_send($fields);
            if($status == 'success') {
            _updateDataStatus($name,$email,$phone,$sku,'pass');
             echo "Successfully Zoho Added :".$email. PHP_EOL;
            } else {
                _updateDataStatus($name,$email,$phone,$sku,'failure'); 
            }
        }
            $i++;
        }

?>