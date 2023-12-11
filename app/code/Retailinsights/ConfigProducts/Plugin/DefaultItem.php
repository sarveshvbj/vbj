<?php

namespace Retailinsights\ConfigProducts\Plugin;

use Magento\Quote\Model\Quote\Item;

class DefaultItem
{
    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $product = $item->getProduct();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface'); 
        $currencyCode = $storeManager->getStore()->getCurrentCurrencyCode(); 
        $currency = $objectManager->create('\Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode); 
            $currencySymbol = $currency->getCurrencySymbol(); 
        $config_price = $item->getData('custom_final_price');
        $config_flag = $item->getData('custom_price_flag');
        if($config_price) {
            $config_price = $currencySymbol.number_format($config_price);
        }

        $atts = [
            "price_flag" => $config_flag,
            "custom_final_price" => $config_price
        ];

        return array_merge($data, $atts);
    }
}