<?php
    /**
     * Iksula Complaint CustomPrice Observer
     *
     * @category    Iksula
     * @package     Webkul_Hello
     * @author      Webkul Software Private Limited
     *
     */
    namespace Iksula\Complaint\Observer;
 
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
/*        public function execute(\Magento\Framework\Event\Observer $observer) {
            $item = $observer->getEvent()->getData('quote_item');         
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            $item = $observer->getQuoteItem();
            $productId = $item->getProductId();
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productCollection = $_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            $productType = $productCollection->getTypeId();
            if($productType !='configurable'){
            $productPriceById = $productCollection->getPrice();
            $offer_percentage = $productCollection->getResource()->getAttribute('offer_percentage')->getFrontend()->getValue($productCollection);
            $gst = round($productCollection->getResource()->getAttribute('tax_amount')->getFrontend()->getValue($productCollection));
            $finalPrice = round($productPriceById);
            if(isset($offer_percentage) && $offer_percentage !='' && $_COOKIE['cookie_name'] =='nonoffer'){
                $customPrice = round($finalPrice - ($finalPrice * $offer_percentage)/100);
            }else{
                $customPrice = $finalPrice;
            }
            $price = $customPrice; //set your price here
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
            }
        }*/
            public function execute(\Magento\Framework\Event\Observer $observer) {
            $item = $observer->getEvent()->getData('quote_item');         
            $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
            $item = $observer->getQuoteItem();
            $productId = $item->getProductId();
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager = $_objectManager->get('\Magento\Store\Model\StoreManagerInterface'); 
            $currencyCode = $storeManager->getStore()->getCurrentCurrencyCode(); 
            $currency = $_objectManager->create('\Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode); 
            $currencySymbol = $currency->getCurrencySymbol(); 
            $productCollection = $_objectManager->create('Magento\Catalog\Model\Product')->load($productId);
            $productType = $productCollection->getTypeId();
            if($productType !='configurable'){
            $productPriceById = $productCollection->getPrice();
            $productSpecialPriceById = $productCollection->getSpecialPrice();
            $offer_percentage = $productCollection->getResource()->getAttribute('smart_percentage')->getFrontend()->getValue($productCollection);
            $discount_diamond_in = $productCollection->getResource()->getAttribute('discount_diamond_in')->getFrontend()->getValue($productCollection);
            $metal_discount_in = $productCollection->getResource()->getAttribute('metal_discount_in')->getFrontend()->getValue($productCollection);
            $discount_making_charge_in = $productCollection->getResource()->getAttribute('discount_making_charge_in')->getFrontend()->getValue($productCollection);
            $gst = round($productCollection->getResource()->getAttribute('tax_amount')->getFrontend()->getValue($productCollection));
            $finalPrice = round($productPriceById);
            if(isset($offer_percentage) && $offer_percentage !='' && $_COOKIE['cookie_name'] == 'nonoffer'){
                echo 'Smart By Product'.'<br>';
                if($currencyCode=='USD' && $currencySymbol=='$'){
                   $customPricess = round($finalPrice - ($finalPrice * $offer_percentage)/100);
                   $customPrices = round(($customPricess)*0.014);
                   $customPrice = round($customPrices + ($customPrices*5.5)/100);
                }else{
                $customPrice = round($finalPrice - ($finalPrice * $offer_percentage)/100);
                }
            }elseif(isset($offer_percentage) && $offer_percentage !='' && $_COOKIE['cookie_name'] == 'null'){
                echo 'non By Product'.'<br>';
                if($currencyCode=='USD' && $currencySymbol=='$'){
                   $customsPrice = round(($finalPrice)*0.014);
                   $customPrice = round($customsPrice + ($customsPrice*5.5)/100);
                }else{
                $customPrice = $finalPrice;
                }
            }
          elseif(isset($discount_diamond_in) || isset($discount_making_charge_in) || isset($metal_discount_in) || $discount_making_charge_in !='' || $discount_diamond_in !='' || $metal_discount_in!=''){
                echo 'offer Product'.'<br>';
                 if(isset($discount_diamond_in)){
                        $dis_per = $discount_diamond_in;
                    }elseif(isset($discount_making_charge_in)){
                        $dis_per = $discount_making_charge_in;
                    }elseif(isset($metal_discount_in)){
                        $dis_per = $metal_discount_in;
                    }
                if($currencyCode=='USD' && $currencySymbol=='$'){
                   $customPricess = $productSpecialPriceById; //round($finalPrice - ($finalPrice * $dis_per)/100);
                   $customPrices = round(($customPricess)*0.014);
                   $customPrice = round($customPrices + ($customPrices*5.5)/100);
                }else{
                $customPrice = $productSpecialPriceById; //round($finalPrice - ($finalPrice * $dis_per)/100);
                }
            }else{
                echo 'simple Product';
                if($currencyCode=='USD' && $currencySymbol=='$'){
                    if(isset($productSpecialPriceById) ){
                    $customPrice = round(($productSpecialPriceById)*0.014);
                    $customPrice = round($customsPrice + ($customsPrice*5.5)/100);
                    }else{
                    $customsPrice = round(($finalPrice)*0.014);
                    $customPrice = round($customsPrice + ($customsPrice*5.5)/100);
                    }
                }else{
                if(isset($productSpecialPriceById) ){
                    $customPrice = $productSpecialPriceById;
                }else{
                    $customPrice = $finalPrice;
                }
                }
            }
            $price = $customPrice; //set your price here
            /*if(isset($productSpecialPriceById) ){
                $price = $productSpecialPriceById;
            }else{
                $price = $customPrice;
            }*/
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
            }
        }
 
    }