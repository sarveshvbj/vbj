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
require('./app/bootstrap.php');	
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');

//get category factory
$categoryCollectionFactory = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
$categoryCollection = $categoryCollectionFactory->create();
$categoryCollection->addAttributeToSelect('*');

$categoryArray = array();

 $export_file = "var/export/categories.csv"; // assumes that you're running from the web root. var/ is typically writable
 $export = fopen($export_file, 'w') or die("Permissions error."); // open the file for writing.  if you see the error then check the folder permissions.

 $output = "";

 $output = "id,name\r\n"; // column names. end with a newline.
 fwrite($export, $output); // write the file header with the column names


 foreach ($categoryCollection as $category) {
     $output = ""; // re-initialize $output on each iteration
     $output .= $category->getId().','; // no quote - integer
     $output .= '"'.$category->getName().'",'; // quotes - string
     // add any other fields you want here 
     $output .= "\r\n"; // add end of line
     fwrite($export, $output); // write to the file handle "$export" with the string "$output".
 }

 fclose($export); // close the file handle.

 ?>