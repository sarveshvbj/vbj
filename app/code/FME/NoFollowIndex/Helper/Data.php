<?php

/**
 * Class for NoFollowIndex Helper Data
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */


namespace FME\NoFollowIndex\Helper;

use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    // Constants
    const XML_GENERAL_ENABLE = 'nofollowindex/nofollowindex_general/nofollowindex_general_enable';
    const XML_ENABLE_CATEGORY = 'nofollowindex/nofollowindex_catprodcms/nofollowindex_catprodcms_category';
    const XML_ENABLE_PRODUCT = 'nofollowindex/nofollowindex_catprodcms/nofollowindex_catprodcms_product';
    const XML_ENABLE_CMS = 'nofollowindex/nofollowindex_catprodcms/nofollowindex_catprodcms_cms';
    const XML_CUSTOM_URL = 'nofollowindex/nofollowindex_customurl/nofollowindex_customurl_url';

    // General Configurations
    public function enableNoFollowIndexExtension()
    {
        return $this->scopeConfig->getValue(self::XML_GENERAL_ENABLE, ScopeInterface::SCOPE_STORE);
    }
    // Categories, Products and CMS Pages Configurations
    public function enableNoFollowIndexForCategories()
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_CATEGORY, ScopeInterface::SCOPE_STORE);
    }
    public function enableNoFollowIndexForProducts()
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_PRODUCT, ScopeInterface::SCOPE_STORE);
    }
    public function enableNoFollowIndexForCMS()
    {
        return $this->scopeConfig->getValue(self::XML_ENABLE_CMS, ScopeInterface::SCOPE_STORE);
    }

    public function getCustomUrl($store = null)
    {
        $customurl =  $this->scopeConfig->getValue(self::XML_CUSTOM_URL, ScopeInterface::SCOPE_STORE);
        return json_decode($customurl, true);
    }
}
