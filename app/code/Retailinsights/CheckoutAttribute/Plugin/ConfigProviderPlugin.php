<?php

namespace Retailinsights\CheckoutAttribute\Plugin;

class ConfigProviderPlugin extends \Magento\Framework\Model\AbstractModel
{

    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {

        $items = $result['quoteItemData'];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        for($i=0;$i<count($items);$i++){

            $quoteId = $items[$i]['item_id'];
            $quote = $objectManager->create('\Magento\Quote\Model\Quote\Item')->load($quoteId);
            $productId = $quote->getProductId();
            $product = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);
            $productFlavours = $product->getResource()->getAttribute('gcm_image_upload')->getFrontend()->getValue($product);         
            if($productFlavours == ''){
                $items[$i]['gcm_image_url'] = '';
            }
            else{
            $items[$i]['gcm_image_url'] = 'https://cdn-media.vaibhavjewellers.com/pub/media/catalog/attribute-data/'.$productFlavours;
            }
        }
        $result['quoteItemData']= $items;
        return $result;
    }

}
