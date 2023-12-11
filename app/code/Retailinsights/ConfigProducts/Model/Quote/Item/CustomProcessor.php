<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Retailinsights\ConfigProducts\Model\Quote\Item;

use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Class Processor
 *  - initializes quote item with store_id and qty data
 *  - updates quote item qty and custom price data
 */
class CustomProcessor extends \Magento\Quote\Model\Quote\Item\Processor
{

 // protected $_helperData;

// protected function _construct(
// \Retailinsights\ConfigProducts\Helper\Data $helperData )
// {
//     $this->_helperData= $helperData;
//     parent::_construct();
// }

    /**
     * Set qty and custom price for quote item
     *
     * @param Item $item
     * @param DataObject $request
     * @param Product $candidate
     * @return void

     */
    private $logger;
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function prepare(Item $item, DataObject $request, Product $candidate): void
    {
        /**
         * We specify qty after we know about parent (for stock)
         */
        $dataHelper = \Magento\Framework\App\ObjectManager::getInstance()->get("\Retailinsights\ConfigProducts\Helper\Data");

        if ($request->getResetCount() && !$candidate->getStickWithinParent() && $item->getId() == $request->getId()) {
            $item->setData(CartItemInterface::KEY_QTY, 0);
        }
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/addToCart.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);*/
        $item->addQty($candidate->getCartQty());
        $status="error";
        $price_result = array();
         if($request->getData('price_flag') == 1) {
             try {
                $id='45';
            $sku=$request->getData('product_sku');
            $ringsize = $request->getData('ring_selected');
            $config_id="";
            $purity = $request->getData('purity_selected');
            $diamond_quality = $request->getData('diamond_selected');
            $price_result = $dataHelper->getCustomPrice($id, $sku, $ringsize, $purity, $diamond_quality);
            $status = $price_result['status'];
            $this->logger->info(print_r($price_result, true));
             } catch(Exception $e) {
                $this->logger->critical($e->getMessage());
             }

             $this->logger->info($status);
             
             if($status != "error") {
                $final_price = floatval($price_result['response']['final_price']);

                $spl_price = floatval($price_result['response']['special_price']);
                $config_id = $price_result['response']['id'];
                if(round($spl_price) != 0) {
                  $final_price = $spl_price; 
                }
                $this->logger->info($final_price);

                //$tax = ($res_price * 3)/100; 
             $customPrice = $this->convertPrice($final_price,null,null);
              $this->logger->info("Thisis custom ".$customPrice);
          }  else {
             $customPrice = $request->getCustomPrice();
          }     
            
         }
         else
         $customPrice = $request->getCustomPrice();

        if (!empty($customPrice)) {
            if(round($spl_price) != 0 && round($spl_price) != round($price_result['response']['final_price'])) {
                  $item->setCustomFinalPrice($this->convertPrice(round($price_result['response']['final_price']),null,null));
                }
            $item->setPurityValue($purity);
            $item->setProductSku($sku);
             $item->setCustomWeight($price_result['response']['net_weight']);
            $item->setRingValue($ringsize);
            $item->setDiamondValue($diamond_quality);
            $item->setConfigId($config_id);
            $item->setCustomPriceFlag("true");
            $item->setCustomPrice($customPrice);
            $item->setOriginalCustomPrice($customPrice);
        }
    }

    public function convertPrice($amount = 0, $store = null, $currency = null)
{
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $priceCurrencyObject = $objectManager->get('Magento\Framework\Pricing\PriceCurrencyInterface'); 
    //instance of PriceCurrencyInterface
    $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
      $priceHelper = $objectManager->get("Magento\Framework\Pricing\Helper\Data");
    //instance of StoreManagerInterface
    if ($store == null) {
        $store = $storeManager->getStore()->getStoreId(); 
        //get current store id if store id not get passed
    }
   $rate = $priceCurrencyObject->convert($amount, $store, $currency);    
    return $rate;

}

  
}
