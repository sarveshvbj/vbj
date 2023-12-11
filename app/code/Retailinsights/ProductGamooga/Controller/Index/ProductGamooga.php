<?php
namespace Retailinsights\ProductGamooga\Controller\Index;

class ProductGamooga extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;


	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory
		
		)
	{
		$this->_pageFactory = $pageFactory;
	
		return parent::__construct($context);
	}

	public function execute()
	{
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
   $connection = $resource->getConnection(); 
   $sql = "SELECT * FROM `product_details_data`";
  $results = $connection->fetchAll($sql);


        $fileName = 'customData.csv';
        $csvFile = '/home/stage2/public_html/pub/gamoogaexport/' . $fileName;
        $handle = fopen($csvFile, 'w');

        fputcsv($handle, array(
            'brand',
			'Category',
            'category ID',
            'Event',
			'Gross Weight',
            'Image URL',
            'Name',
			'Net Weight',
            'Price',
            'Product URL',
            'Purity',
			'SKU',
            'Style',
            'Weight',
        ));


        foreach ($results as $userData) {

           
            fputcsv($handle, array(
                
				$userData['brand'],
                $userData['categories'],
                $userData['category_ids'],
                $userData['event'],
                $userData['gross_weight'],
                $userData['image_url'],
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

		
	}


}