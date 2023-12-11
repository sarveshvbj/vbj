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

function _getMobileNumber($customerId = 0) {
     $mobAttrId    = _getAttributeId('mobile');
     $connection = _getConnection('core_read');
      $sql = "SELECT value FROM " . _getTableName('customer_entity_varchar') . " WHERE entity_id = ? AND attribute_id = ?";
       $sql1 = "SELECT telephone FROM " . _getTableName('customer_address_entity') . " WHERE parent_id = ?";
    $reg_mobile = $connection->fetchOne(
        $sql,
        [
            $customerId,
            $mobAttrId
        ]
    );

    if(empty($reg_mobile)) {
         $reg_mobile = $connection->fetchOne(
        $sql1,
        [
            $customerId
        ]
    );
    }
    return $reg_mobile;
}
function _getProductSku($quoteId = 0) {
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
    $sql        = "SELECT COUNT(*) AS count_no FROM " . _getTableName('zoho_abondoned_cart') . " WHERE email = ? AND mobile = ?";
    return $connection->fetchOne($sql, [$email,$phone]);
}
function _checkIfLeadSkuExists($email,$phone,$sku)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT COUNT(*) AS count_no FROM " . _getTableName('zoho_abondoned_cart') . " WHERE email = ? AND mobile = ? AND sku = ?";
    return $connection->fetchOne($sql, [$email,$phone,$sku]);
}

function _insertDataStatus($name,$email,$phone,$sku,$status) {
     $connection   = _getWriteConnection();
     $updatedAtFrom = date_create()->format('Y-m-d H:i:s');
    // echo $updatedAtFrom;

    $sql1 = "INSERT INTO " . _getTableName('zoho_abondoned_cart') . " (name,email,mobile,sku,status,updated_at) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), sku=VALUES(sku), status=VALUES(status), updated_at=VALUES(updated_at)";
    $connection->query(
        $sql1,
        [
            $name,
            $email,
            $phone,
            $sku,
            $status,
            $updatedAtFrom
        ]
    );
   
}
function _updateDataStatus($name,$email,$phone,$sku,$status) {
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


$updatedAtFrom = date('d-m-Y H:i:s'); //Add current time
$updatedAtTo   = date("Y-m-d H:i:s", strtotime("+30 minutes"));  // $updatedAtFrom  + 90 minutes ,add 90 minutes in current time to abondened cart

        $connection =_getReadConnection();


        $select = $connection->select()
            ->from(
                ['q' => _getTableName('quote')],
                [
                    'store_id'    => 'q.store_id',
                    'quote_id'    => 'q.entity_id',
                    'customer_id' => 'q.customer_id',
                    'updated_at'  => 'q.updated_at',
                    'created_at'  => 'q.created_at',
                ]
            )
            ->joinLeft(
                ['qa' => _getTableName('quote_address')],
                'q.entity_id = qa.quote_id AND qa.address_type = "billing"',
                [
                    'customer_email'     => new \Zend_Db_Expr('IFNULL(q.customer_email, qa.email)'),
                    'customer_firstname' => new \Zend_Db_Expr('IFNULL(q.customer_firstname, qa.firstname)'),
                    'customer_lastname'  => new \Zend_Db_Expr('IFNULL(q.customer_lastname, qa.lastname)'),
                    'telephone'  => new \Zend_Db_Expr('IFNULL(q.telephone, qa.telephone)'),
                ]
            )
            ->joinInner(
                ['qi' => _getTableName('quote_item')],
                'q.entity_id = qi.quote_id',
                [
                    'i_created_at' => new \Zend_Db_Expr('MAX(qi.created_at)'),
                ]
            )
            ->joinLeft(array('order' => _getTableName('sales_order')),
                'order.quote_id = q.entity_id',
                array()
            )
            ->where('order.entity_id IS NULL')
            ->where('q.is_active = 1')
            ->where('q.items_count > 0')
            ->where('q.customer_email IS NOT NULL OR qa.email IS NOT NULL')
            ->where('qi.parent_item_id IS NULL')
            ->group('q.entity_id')
            ->having(
                '(q.created_at > ? OR MAX(qi.created_at) > ?)',
                $updatedAtFrom
            )
            ->having(
                '(q.created_at < ? OR MAX(qi.created_at) < ?)',
                $updatedAtTo
            )
            ->order('q.updated_at');

        $quotes = $connection->fetchAll($select);

       $array = array();
                    //$fields = json_encode(array("data" => ));
      // $i=0;
        foreach ($quotes as $quote) {
             $timestamp = strtotime(max($quote['created_at'], $quote['i_created_at']));
            if (date('Y-m-d', $timestamp) > '2020-03-31' && _getMobileNumber($quote['customer_id'])) {
               
                $email = $quote['customer_email'];
                $name = $quote['customer_firstname'] . ' ' . $quote['customer_lastname'];
               
                echo $name." ". PHP_EOL;
                $phone = _getMobileNumber($quote['customer_id']);
                $sku   = _getProductSku($quote['quote_id']);
                $count = _checkIfLeadExists($email,$phone);
                if($count){
                  if(!_checkIfLeadSkuExists($email,$phone,$sku)) {
                    _updateDataStatus($name,$email,$phone,$sku,'update');
                  }
                } else {
                  _insertDataStatus($name,$email,$phone,$sku,'new');
                }

            } 
        }

?>