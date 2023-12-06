<?php
namespace Vaibhav\Sortby\Controller\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Stock extends \Magento\Framework\App\Action\Action  {


    protected $_product;
 
    /**
     * @var Magento\CatalogInventory\Api\StockStateInterface 
     */
    protected $_stockStateInterface;
 
    /**
     * @var Magento\CatalogInventory\Api\StockRegistryInterface 
     */
    protected $_stockRegistry;

    //protected $_logFactory;

    public function __construct(\Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\CatalogInventory\Api\StockStateInterface $stockStateInterface,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
        //\Vaibhav\Sortby\Model\LogFactory $logFactory
        ) {
        $this->order = $order;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
        $this->_product = $product;
        $this->_stockStateInterface = $stockStateInterface;
        $this->_stockRegistry = $stockRegistry;
        //$this->_logFactory = $logFactory;
        parent::__construct($context);

    }
   /*  public function updateProductStock($productId,$stockData) {
        $product=$this->_product->load($productId); //load product which you want to update stock
        $stockItem=$this->_stockRegistry->getStockItem($productId); // load stock of that product
        $stockItem->setData('is_in_stock',$stockData['is_in_stock']); //set updated data as your requirement
        $stockItem->setData('qty',$stockData['qty']); //set updated quantity 
        $stockItem->setData('manage_stock',$stockData['manage_stock']);
        $stockItem->setData('use_config_notify_stock_qty',1);
        $stockItem->save(); //save stock of item
        $product->save(); //  also save product
    }*/

    public function execute() {

        $base_url = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        // lOGGER
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/stock_inventory_cron.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $logger->info('-----');
        $logger->info('Start SI drop cron');*/


        //echo $dateTime = date('Y-m-d H:i:s');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('taged_values');
                 /*echo $sql1= "Update $tableName Set flag ='y' where tagkey = '10VG2203'";
                 $connection->query($sql1);*/
                 $sql = "SELECT * FROM `taged_values` where flag='Y'";
                 $results = $connection->fetchAll($sql); 
                 /*echo '<pre>'; 
                 print_r($results); 
                 echo '</pre>';*/
                 //die();
                 if(count($results)>0){
                     foreach ($results as  $result) {
                    //echo "Sku - ----".$tagkey = $result['tagkey'].'<br>';
                    //echo "Qty - ----".$pieces = $result['pieces'].'<br>';
                   $productSku = $result['tagkey'];
                   $qty = $result['pieces'];
                   $stockItems = $objectManager->get('Magento\CatalogInventory\Model\StockRegistry');
                   $stockItem = $stockItems->getStockItemBySku($productSku);
                   $stockItem->setQty($qty);
                   $stockItem->save();
                   /*$stocktaged = $objectManager->get('Vaibhav\Sortby\Model\logFactory');
                   print_r(get_class_methods($stocktaged));
                   $stocktagedvales = $stocktaged->getTagkey($productSku);
                   $stocktagedvales->setTransUpdate('U');
                   $stocktagedvales->save();*/
                   //$this->updateProductStock($productSku,$qty);
                 /*  echo '<br>';
                  echo $sql1= "Update taged_values Set trans_update ='U' where 'tagkey' = '" . $productSku . "'";
                   $connection->query($sql1);
                     echo '</br>';
                   $logger->info('Start SI drop cron');*/
                   if($stockItem->save()){
                    //echo "stock update";
                    //echo '<br>';
                     $sql1= "Update taged_values Set flag ='U' where tagkey = '" . $productSku . "'";
                     $connection->query($sql1);
                     //echo '</br>';
                   }
                 }
                 }
    }
}