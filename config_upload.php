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
$fileDriver = $objectManager->get('\Magento\Framework\Filesystem\Driver\File');
$csvParser = $objectManager->get('\Magento\Framework\File\Csv');
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$varPath  =  $directory->getPath('var');

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

function _getPriceId($data) {
     $connection = _getConnection('core_read');
      $sql = "SELECT sku FROM " . _getTableName('quote_item') . " WHERE quote_id = ? ";
    $quote_collection = $connection->fetchAll(
        $sql,
        [
            $quoteId
        ]
    );
    $skus='';
    foreach ($quote_collection  as $quote) {
        $skus.=$quote['sku'].' ';
    }
    return $skus;
}
function _checkIfLeadExists($email,$phone)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT id FROM " . _getTableName('custom_config_products') . " WHERE sku = ? AND mobile = ?";
    return $connection->fetchOne($sql, [$email,$phone]);
}
function _checkIfValueExists($data)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT id FROM " . _getTableName('custom_config_products') . " WHERE sku = ? AND size = ? AND diamond_color = ? AND diamond_quality = ? AND purity = ?";
    return $connection->fetchOne($sql, [$data['sku'],$data['size'],$data['diamond_color'],$data['diamond_quality'],$data['purity']]);
}

function _uploadProduct($entity_data, $price_data) {

     try {  

            $tableEntity = _getTableName('custom_config_products');
            $tablePrice = _getTableName('custom_config_products_price');
            $last_id_arr = array();
            $last_id = 0;
           $connection   = _getWriteConnection();
          if(!_checkIfValueExists($entity_data)) {
            $entity_data['created_at'] = date('Y-m-d H:i:s');
            $entity_data['updated_at'] = date('Y-m-d H:i:s');
              echo "A-SKU:".$entity_data['sku'].": R-S".$entity_data['size'].'||';
            if($connection->insertMultiple($tableEntity, $entity_data)) {
                $last_id = $connection->lastInsertId();
            }
         if($last_id != 0) {
             $last_id_arr['id'] = $last_id;
             $newPriceData = array_merge($last_id_arr,$price_data);
             $connection->insertMultiple($tablePrice, $newPriceData);
         }

          } 
          else {
             
            $last_id = _checkIfValueExists($entity_data);
             $entity_data['updated_at'] = date_create()->format('Y-m-d H:i:s');
             if($last_id != 0 || $last_id != "" || $last_id != null) {
             $last_id_arr['id'] = $last_id;
             $new_entity = array_merge($last_id_arr,$entity_data);
             $new_price = array_merge($last_id_arr, $price_data);
              if($connection->insertOnDuplicate($tableEntity,$new_entity)) {
                $connection->insertOnDuplicate($tablePrice, $new_price);
                 echo "U-SKU:".$new_entity['sku']." R-S:".$new_entity['size'].'||';
              }
             
             }

          }
           



        } catch (\Exception $e) {
            if ($e->getCode() === 23000
                && preg_match('#SQLSTATE\[23000\]: [^:]+: 1062[^\d]#', $e->getMessage())
            ) {
                throw new \Magento\Framework\Exception\AlreadyExistsException(
                    __('URL key for specified store already exists.')
                );
            }
            throw $e;
        }
    
     $updatedAtFrom = date_create()->format('Y-m-d H:i:s');
}

function _updateDataStatus($data) {
     $connection   = _getWriteConnection();
     $updatedAtFrom = date_create()->format('Y-m-d H:i:s');
     $sql2 = "UPDATE " . _getTableName('zoho_abondoned_cart') ." SET status= ?, $sku= ?, updated_at=?  WHERE mobile = ? and email = ? ";
    $connection->query(
        $sql2,
        [
            $status,
            $sku,
            $updatedAtFrom,
            $phone,
            $email 
        ]
    );
   
}

    function getFileData(): array
    {
         global $varPath;
         global $fileDriver;
         global $csvParser;
        $data = [];
        $dirPath = $varPath."/";
        $fname = "live_config_custom_price.csv";
        $filepath = $dirPath.$fname;

        if ($fileDriver->isExists($filepath)) {
            $csvParser->setDelimiter(',');
            $data = $csvParser->getData($filepath);
        }
        return $data;
    }

$updatedAtFrom = date('d-m-Y H:i:s');
        $connection =_getReadConnection();

       $array = getFileData();
       for($i=count($array)-1; $i > 0; $i--) {
    if(trim($array[$i][0]) == '' &&  trim($array[$i][1]) == '' &&  trim($array[$i][2]) == '' &&  trim($array[$i][3]) == '' &&  trim($array[$i][4]) == '') {
       unset($array[$i]);
    }
}

$rows = count($array[0]);
$headers = array();
for ($j = 0; $j < $rows; $j++) {
    $cols = $array[0][$j];
    if(!empty($cols)){
        $headers[] = trim($cols);
    }
}
unset($array[0]);
$array = array_values($array);
$counts=count($array);
//print_r($array);

for ($v = 0; $v < $counts; $v++) {
    $firstTableData = array();
    $secondTableData = array();
   // echo "Data Row - ".$v.PHP_EOL;
    for ($j = 0; $j < $rows; $j++) {
        //$first[$headers[$j]] = $array[$v][$j];
        if($j <= 17) {
             $firstTableData[$headers[$j]] =  trim($array[$v][$j]);
        } else if($j >= 18) {
            $secondTableData[$headers[$j]] =  trim($array[$v][$j]);
        }
}

_uploadProduct($firstTableData,$secondTableData);
    
}


?>