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
use Magento\Framework\App\Filesystem\DirectoryList;
require('app/bootstrap.php');	
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();   
    $attributesCollection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Eav\Attribute');
    $attributeInfo = $attributesCollection->getCollection();
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=attributeinfo.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
$fp = fopen('php://output', 'w');

$csvHeader = array("attribute_code","frontend_label","frontend_input","is_unique","is_unique","is_required","is_user_defined","default_value");
fputcsv( $fp, $csvHeader,",");
    foreach($attributeInfo as $attributes)
   {
        $attributeId = $attributes->getAttributeId();
        $attributeCode = $attributes->getAttributeCode();
        $attributeLabel = $attributes->getFrontendLabel();
        $attributeFronendInput = $attributes->getFrontendInput();
        $attributeIsUnique = $attributes->getIsUnique();
        $attributeIsRequired = $attributes->getIsRequired();//is_user_defined
        $attributeIsUserDefined = $attributes->getIsUserDefined();
        $attributeDefaultValue = $attributes->getDefaultValue();
        /*echo '<pre>';
        print_r($attributes->getData());
        echo '</pre>';*/
        fputcsv($fp, array($attributeCode,$attributeLabel,$attributeFronendInput,$attributeIsUnique,$attributeIsRequired,$attributeIsUserDefined, $attributeDefaultValue),   ",");
   }
   fclose($fp); 

?>
