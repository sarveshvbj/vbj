<?php

namespace Vaibhav\Sortby\Model;

class Config extends \Magento\Catalog\Model\Config
{

    /**
     * Retrieve Attributes Used for Sort by as array
     * key = code, value = name
     *
     * @return array
     */
    public function getAttributeUsedForSortByArray()
    {
        $options = ['position' => __('Position')];
        foreach ($this->getAttributesUsedForSortBy() as $attribute) {
            /* @var $attribute \Magento\Eav\Model\Entity\Attribute\AbstractAttribute */
            $options[$attribute->getAttributeCode()] = $attribute->getStoreLabel();
        }

        /*$options['position'] = __('Sort By');*/
        /*$options['price'] =  __('Price: Low to High');
        $options['price_desc'] = __('Price: High to Low');*/
        /*$options['newest'] = __('Newest');*/
        //$options['oldest'] = __('Oldest');
        /*$options['name_az'] = __('Product Name A - Z');
        $options['name_za'] = __('Product Name Z - A');*/
        return $options;
    }

}
