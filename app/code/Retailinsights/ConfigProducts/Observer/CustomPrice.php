<?php
    /**
     * Iksula Complaint CustomPrice Observer
     *
     * @category    Iksula
     * @package     Webkul_Hello
     * @author      Webkul Software Private Limited
     *
     */
    namespace Retailinsights\ConfigProducts\Observer;
 
    use Magento\Framework\Event\ObserverInterface;
    use Magento\Framework\App\RequestInterface;
    ob_start();
    class CustomPrice implements ObserverInterface
    {
    public function __construct(
    \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->_request = $request;
    }
            public function execute(\Magento\Framework\Event\Observer $observer) {
            $item = $observer->getEvent()->getData('quote_item');         
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            $item = $observer->getQuoteItem();
            $productId = $item->getProductId();
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $_objectManager->get('\Magento\Store\Model\StoreManagerInterface'); 
            $dataHelper = $_objectManager->get('\Retailinsights\ConfigProducts\Helper\Data');
            $currencyCode = $storeManager->getStore()->getCurrentCurrencyCode(); 
            $currency = $_objectManager->create('\Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode); 

            $currencySymbol = $currency->getCurrencySymbol(); 
            $resource = $_objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $priceManagerData = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");
            $inr_to_usd=$priceManagerData['usd'];
            $productCollection = $_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            $productType = $productCollection->getTypeId();
             $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/retail_cust_price.log');
                               $logger = new \Zend\Log\Logger();
                               $logger->addWriter($writer);

           if($productType == 'configurable') {
               $logger->info('Type'.$productType);
              if($item->getCustomPriceFlag() == "true") {
                 $logger->info('Quote Id'.$item->getQuoteId());
                 //$dataHelper->updateQuoteData($item->getQuoteId(),"true");
              }
                } else if($productType == 'virtual'){
                   $logger->info('Type'.$productType);
                    $logger->info('Quote Id'.$item->getQuoteId());
                }
        }
 
    }