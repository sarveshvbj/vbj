<?php
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
require('app/bootstrap.php');	
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

/*============== CSV File Read =========*/

$file_path = 'prices.csv';


$catRemoveSKUs = readSkuCsv($file_path);
/*echo "<pre>";
print_r($catRemoveSKUs);
echo "</pre>";
exit();*/

$productRepository = $obj->get('\Magento\Catalog\Model\ProductRepository');

foreach($catRemoveSKUs as $productSku => $productprice){
	$_product = $productRepository->get($productSku);
	//print_r($_product->getCategoryIds());
	$_product->setPrice($productprice);
	$_product->save();
	echo $productSku.": ".$productprice;
	echo PHP_EOL;
}


echo "Price has been set Scucessfully Done";

function readSkuCsv($file_path){
	$_sku_array = array();
	$file = fopen($file_path, 'r');
	while (($line = fgetcsv($file)) !== FALSE) {
	  	$sku= $line[0];
		$_sku_array[$sku] = $line[1];
	}
	unset($_sku_array['sku']);
	fclose($file);
	return $_sku_array;
}

?>