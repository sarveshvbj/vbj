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

function _checkIfValueExists($data)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT id FROM " . _getTableName('zoho_Leads_data') . " WHERE lead_id = ?";
    return $connection->fetchOne($sql, [$data['lead_id']]);
}

function uploadData($datas) {
 $last_id = 0;
    $tableEntity = _getTableName('zoho_Leads_data');
     $connection   = _getWriteConnection();
     $last_id_arr = array();
     if(!_checkIfValueExists($datas)) {
        $connection->insertMultiple($tableEntity, $datas);
        $last_id = $datas['lead_id'].'- Added';
        } else {
            $last_id = _checkIfValueExists($datas);
            $last_id_arr['id'] = $last_id;
            $newData = array_merge($last_id_arr,$datas);
            unset($newData['created_at']);
            $newData['updated_at'] = date('Y-m-d H:i:s');
            $connection->insertOnDuplicate($tableEntity,$newData);
            $last_id = $datas['lead_id'].'- Updated';
        }
     
     return $last_id;
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

function _getZohoRecords($fields) {
    $status = array();
       $latest_token = _getLatestZohoToken();
          if(!empty($latest_token)) {
           $arr = _sendZohoApi($fields,$latest_token);
           if(!isset($arr['data'])) {
            $refreshtoken = _refZohoToken();
            _setLatestZohoToken($refreshtoken);
            $status = _sendZohoApi($fields,$refreshtoken);
           } else {
              $status = $arr;
           }
          }  
      return $status;
}

function _sendZohoApi($fields,$reftoken) {
  $status =array("status"=>"error");
    $curl = curl_init('https://www.zohoapis.com/crm/v2/coql');
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
            if(isset($arr['data'])) {
            $status = $arr;
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

     $time =  date('Y-m-d', strtotime("-2 days")).'00:00:00';
$datetime = new \DateTime($time);
$dateFrom = $datetime->format(\DateTime::ATOM);

  $time =  date('Y-m-d', strtotime("-1 days")).'23:59:59';
$datetime = new \DateTime($time);
$dateTo = $datetime->format(\DateTime::ATOM);



    $query = "select Full_Name,Email,Phone,Lead_Status,Lead_Source,Lead_Type,Main_Category,Product_Category,Lead_Country,Budget_Range,Referrer from Leads where Created_Time between '".$dateFrom."' and '".$dateTo."'";

    $fields = array("select_query" => $query);

    $response = _getZohoRecords(json_encode($fields));

    if($response) {
        $table_data = array();
        $response = $response['data'];
        foreach ($response as $value) {
            $table_data[]= uploadData(arrayReplaceKey($value,'id','lead_id','Full_Name','name'));
        }
       print_r($table_data);
    }
    function arrayReplaceKey($array, $oldKey, $newKey,$oldKey1,$newKey1) {
    $r = array();
    foreach ($array as $k => $v) {
        if ($k === $oldKey) $k = $newKey;
        if ($k === $oldKey1) $k = $newKey1;
        $r[strtolower($k)] = $v;
        $r['flag'] = 'Y';
        $r['event'] = 'Zoho Leads';
        $r['created_at'] = date_create()->format('Y-m-d H:i:s');
        $r['updated_at'] = date_create()->format('Y-m-d H:i:s');

    }
    return $r;
}

?>