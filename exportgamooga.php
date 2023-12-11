<?php 
use Magento\Framework\App\Bootstrap; 
require('app/bootstrap.php');   
 
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection(); 
$sql = "SELECT * FROM `product_details_data`";
$results = $connection->fetchAll($sql);

$fileName = 'NewVaibhavProducts.csv';
        $csvFile = '/home/vaibhavjeweller/public_html/pub/' . $fileName;
        $handle = fopen($csvFile, 'w+');

        fputcsv($handle, array(
            'brand',
            'category',
            'category_id',
            'event',
            'gross_weight',
            'image_url',
            'gcm_image_url',
            'name',
            'net_weight',
            'price',
            'product_url',
            'purity',
            'sku',
            'style',
            'weight',
        ));


        foreach ($results as $userData) {

           
            fputcsv($handle, array(
                
                $userData['brand'],
                $userData['categories'],
                $userData['category_ids'],
                $userData['event'],
                $userData['gross_weight'],
                $userData['image_url'],
                $userData['gcm_image_url'],
                $userData['name'],
                $userData['net_weight'],
                $userData['Price'],
                $userData['product_url'],
                $userData['purity'],
                $userData['sku'],
                $userData['style'],
                $userData['weight'],
                

            ));
        }

//        echo $handle;
        fclose($handle);
        header("Content-type: text/csv");
        header("Content-disposition: attachment; filename = " . $fileName);
  readfile($csvFile);

