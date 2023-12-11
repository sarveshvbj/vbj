<?php 
date_default_timezone_set("Asia/Kolkata");
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
function _getAllData($start_from,$results_per_page)
{
    $connection = _getConnection('core_read');
    $sql = "SELECT * FROM " . _getTableName('custom_config_products') . " ORDER BY ID ASC LIMIT ".$start_from.",".$results_per_page;

    return $connection->fetchAll($sql);
}
function _getCount()
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT COUNT(*) AS count FROM " . _getTableName('custom_config_products');
    return $connection->fetchOne($sql);
}

function _getPriceManager()
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT * FROM " . _getTableName('pricemanager')." WHERE id = 1";
    return $connection->fetchAll($sql);
}

function _getCustomPriceValues($id)
{
    $connection = _getConnection('core_read');
    $sql        = "SELECT * FROM " . _getTableName('custom_config_products_price')." WHERE id =".$id;
    return $connection->fetchAll($sql);
}

function _updateData($request_arr) {
     $connection   = _getWriteConnection();
     $updatedAtFrom = date_create()->format('Y-m-d H:i:s');
     $sql2 = "UPDATE " . _getTableName('custom_config_products') ." SET metal_rate= ?, updated_at=?  WHERE id= ? ";
    $main_query = $connection->query(
        $sql2,
        [
            $request_arr['metal_rate'],
            $updatedAtFrom,
            $request_arr['id']
        ]
    );
    if($main_query) {
        $sql_price = "UPDATE " . _getTableName('custom_config_products_price') ." SET metal_price= ?, after_dis_metal_price = ?, wastage_price= ?, after_dis_wastage_price=?, after_dis_making_price=?, making_price= ?, after_dis_diamond_price=?, final_price=?, special_price =?, tax=? WHERE id= ? ";

        $connection->query(
        $sql_price,
        [
            $request_arr['metal_amount'],
            $request_arr['metal_discount'],
            $request_arr['wastage_amount'],
            $request_arr['wastage_discount'],
            $request_arr['making_discount'],
            $request_arr['makingcharges'],
            $request_arr['diamond_discount'],
            $request_arr['final_price'],
            $request_arr['special_price'],
            $request_arr['tax'],
            $request_arr['id']
        ]
    );
        echo $request_arr['sku'];
    }  
}



    $total_count = _getCount();
    $results_per_page = 30;
$total_pages = ceil($total_count / $results_per_page); // calculate total pages with results
  $priceManagerData = _getPriceManager();
for ($i=1; $i<=$total_pages; $i++) { 
    $start_from = ($i-1) * $results_per_page;
    $getresults = _getAllData($start_from,$results_per_page);
  foreach ($getresults as $value) {

         $weight = (float)$value['net_weight'];
         $wastage = (int)$value['wastage']; 
         $making_charge = (int)$value['making_charge'];
         $metal = $value['purity'];
         $metalDiscount = (int)$value['dis_metal_price'];
         $diamondDiscount =(int)$value['dis_diamond_price'];
         $makingChargeDiscount =(int) $value['dis_making_price'];
         $wastageDiscount = (int)$value['dis_wastage_price'];
         
        $metalRate = $priceManagerData[0][strtoupper($metal)];

        $wastageweight = $wastage*($weight/100);
        $wastageamnt = $wastageweight*$metalRate;
        $makingcharges = $weight*$making_charge;
        $metalamnt = $weight*$metalRate;
         $price_values = _getCustomPriceValues($value['id']);
        
        $stoneRate = (float)$price_values[0]['stone_price']+(float)$price_values[0]['diamond_price'];
    
        $finalprice = $wastageamnt + $makingcharges + $metalamnt + $stoneRate;

        $taxamount_customer = ($finalprice * 3)/100;

        $metalDiscountValue = ($metalRate*$weight)*($metalDiscount/100);
        $wastageDiscountValue = $wastageamnt*($wastageDiscount/100);
        $diamondDiscountValue =  ((float)$price_values[0]['diamond_price']+(float)$price_values[0]['stone_price'])*$diamondDiscount/100;
        $final_diamond_discount =  (float)$stoneRate - (float)$diamondDiscountValue;

        $makingRate = $making_charge*$weight; 
        
        $makingChargeDiscountValue = $makingChargeDiscount*$makingRate/100 ;
         
        $grandDiscount = $metalDiscountValue + $diamondDiscountValue + $makingChargeDiscountValue+$wastageDiscountValue;
         $totalSpecialPrice = $finalprice - $grandDiscount;

         if($totalSpecialPrice != $finalprice) {
            $taxamount_customer = ($totalSpecialPrice * 3)/100;
         }

         $request_arr= array("id"=>$value['id'],"sku"=>$value['sku'],"metal_rate"=>$metalRate,"makingcharges"=>$makingcharges,"metal_amount"=>$metalamnt,"wastage_amount"=>$wastageamnt,"diamond_amount"=>$stoneRate,"metal_discount"=>$metalDiscountValue,"diamond_discount"=>$final_diamond_discount,"making_discount"=>$makingChargeDiscountValue,"wastage_discount"=>$wastageDiscountValue,"final_price"=>$finalprice,"special_price"=>$totalSpecialPrice,"tax"=>$taxamount_customer);
         //print_r($request_arr);
         
         // echo $totalSpecialPrice.PHP_EOL;
         _updateData($request_arr);


  }
            
}

?>