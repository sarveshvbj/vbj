<?php
date_default_timezone_set("Asia/Calcutta");
$now        = date("Y-m-d H:i:s");
$date = date('Y-m-d H:i:s', strtotime("-7 day", strtotime($now)));

use Magento\Framework\App\Bootstrap; 
require('app/bootstrap.php');	
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');

$collection = $productCollection->create()->addAttributeToSelect('*')->addAttributeToFilter('stock_status',412)->addAttributeToFilter('created_at', array(
    'from'  => $date,
    'to'    => $now
))->load();

  $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
 $connection = $resource->getConnection();
$sql = "SELECT * FROM `product_details_data`";
$results = $connection->fetchAll($sql);


if(count($results)>0){
    $connection->truncateTable('product_details_data');
}

foreach ($collection as $getAllproduct) {
    $getAllvalues=$getAllproduct->getData();
    
    $sku =$getAllvalues['sku'];
   // $price =$getAllvalues['price'];
    $name =$getAllvalues['name'];
    $style =$getAllproduct->getResource()->getAttribute('style')->getFrontend()->getValue($getAllproduct);;
    $brand ='Vaibhav';
    $event ='new_product';
   
    $net_weight =$getAllvalues['net_weight'];
    $gross_weight =$getAllvalues['gross_weight_approx_gms'];
    $createDate=date('Y-m-d H:i:s');
  
    
    if(isset($getAllvalues['weight'])){
       $weight=$getAllvalues['weight'];
    }
    else{
        $weight='';
    }
    if(isset($getAllvalues['price'])){
       $price =$getAllvalues['price'];
    }
    else{
        $price='';
    }
    if(isset($getAllvalues['purity'])){
      $purity =$getAllproduct->getResource()->getAttribute('purity')->getFrontend()->getValue($getAllproduct);
    }
    else{
        $purity ='';
    }
    if(isset($getAllvalues['gcm_image_upload'])){
       $imagesurl='https://cdn-media.vaibhavjewellers.com/pub/media/catalog/attribute-data/'.$getAllvalues['gcm_image_upload'];
    }
    else{
        $imagesurl='';
    }
    
    $categoryid = $getAllproduct->getCategoryIds();
$getcat=implode(',',$categoryid);
    
   // print_r($getAllproduct->getProductUrl());
   
   
   
   
   $getproducturl=$getAllproduct->getProductUrl();
$attribute   = $getAllproduct->getResource()->getAttribute('image');
$productimageUrl  = $attribute->getFrontend()->getUrl($getAllproduct);
$categeroyvalues = array();
foreach($categoryid as $category){
    $cat = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
    array_push($categeroyvalues, $cat->getName());
    }
 $insertcategoryname=implode(',', $categeroyvalues);
 $sqlinsert = "INSERT INTO product_details_data(image_url, gcm_image_url, product_url, category_ids, categories, sku, name, purity, price, brand, event, created_at, weight, style, gross_weight, net_weight) VALUES ('".$productimageUrl."', '".$imagesurl."', '".$getproducturl."', '".$getcat."', '".$insertcategoryname."', '".$sku."', '".$name."', '".$purity."', '".$price."', '".$brand."', '".$event."', '".$createDate."', '".$weight."', '".$style."', '".$gross_weight."', '".$net_weight."')";
 $connection->query($sqlinsert);

}

echo "Successfully Updated";
?>