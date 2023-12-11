<?php
/**
 * Bss Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   Bss
 * @package    Bss_ProductsWidgetSlider
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 Bss Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */

namespace Bss\ProductsWidgetSlider\Block;

/**
 * Class GetData
 *
 * @package Bss\ProductsWidgetSlider\Block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GetData extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    const DEFAULT_WEBSITE = 0;
    const DEFAULT_SHOW_PRICE = false;
    const DEFAULT_SHOW_CART = false;
    const DEFAULT_SHOW_WISH_LIST = false;
    const DEFAULT_SHOW_COMPARE = false;
    const DEFAULT_SHOW_OUT_OF_STOCK = false;
    const DEFAULT_COLLECTION_SORT_BY = 'name';
    const DEFAULT_COLLECTION_ORDER = 'ASC';
    const DEFAULT_SHOW_AS_SLIDER = false;
    const DEFAULT_PRODUCTS_PER_SLIDE = 5;
    const DEFAULT_SHOW_NAV_BAR = true;
    const DEFAULT_SHOW_PREV_NEXT = true;
    const DEFAULT_AUTO_EVERY = 0;

    /**
     * @var PriceCurrency
     */
    protected $priceCurrency;

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->start();
    }

    /**
     * Start
     *
     * @return $this
     */

    public function start()
    {
        return $this;
    }
    /**
     * Get post parameters
     *
     * @param $product
     * @return array
     */
    public function getAddToCartPostParamsAjax($product)
    {
        return $this->getAddToCartPostParams($product);
    }

    /**
     * Get Enable Config
     *
     * @return mixed
     */
    public function getEnableConfig()
    {
        return $this->getData('bssHelper')->getEnable();
    }
    /**
     * Product Attribute
     *
     * @param object $product
     * @param string $attributeHtml
     * @param string $attributeName
     * @return string
     */
    public function productAttribute($product, $attributeHtml, $attributeName)
    {
        return $this->getData('Output')->productAttribute($product, $attributeHtml, $attributeName);
    }

    /**
     * Get Post Data
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    public function getPostData($url, array $data = [])
    {
        return $this->getData('postHelper')->getPostData($url, $data);
    }

    /**
     * Is Allow
     *
     * @return bool
     */
    public function isAllow()
    {
        return $this->getData('wishListHelper')->isAllow();
    }

    /**
     * Get Post Data Params
     *
     * @param $product
     * @return mixed
     */
    public function getPostDataParams($product)
    {
        return $this->getData('compareHelper')->getPostDataParams($product);
    }

    /**
     * Set Cache
     *
     * @return $this
     */
    public function setCache()
    {
        return $this->setData('cache_lifetime', '0');
    }

    /**
     * Gen key
     *
     * @return string
     */
    public function genKey()
    {
        return uniqid();
    }

    /**
     * Get Widget Name
     *
     * @return string
     */
    public function getWidgetName()
    {
        if (!$this->hasData('title')) {
            $this->setData('title');
        }
        return $this->getData('title');
    }

    /**
     * Return website id
     *
     * @return int
     */
    public function getWebsiteID()
    {
        if (!$this->hasData('websiteID')) {
            $this->setData('websiteID', self::DEFAULT_WEBSITE);
        }
        return $this->getData('websiteID');
    }

    /**
     * Get From Date Value
     *
     * @return string
     */
    public function getFromDate()
    {
        if (!$this->hasData('from_date')) {
            $this->setData('from_date');
        }
        return $this->getData('from_date');
    }

    /**
     * Get From To Value
     *
     * @return string
     */
    public function getToDate()
    {
        if (!$this->hasData('to_date')) {
            $this->setData('to_date');
        }
        return $this->getData('to_date');
    }

    /**
     * @return mixed
     */
    public function getSortBy()
    {
        if (!$this->hasData('collection_sort_by')) {
            $this->setData('collection_sort_by', self::DEFAULT_COLLECTION_SORT_BY);
        }
        return $this->getData('collection_sort_by');
    }

    /**
     * Get Store Order
     *
     * @return mixed
     */
    public function getSortOrder()
    {
        if (!$this->hasData('collection_sort_order')) {
            $this->setData('collection_sort_order', self::DEFAULT_COLLECTION_ORDER);
        }
        return $this->getData('collection_sort_order');
    }

    /**
     * Get Show Product Price
     *
     * @return bool
     */
    public function isShowPrice()
    {
        if (!$this->hasData('show_price')) {
            $this->setData('show_price', self::DEFAULT_SHOW_PRICE);
        }
        return $this->getData('show_price');
    }

    /**
     * Get Show Add to Cart Button
     *
     * @return bool
     */
    public function isShowCart()
    {
        if (!$this->hasData('show_add_to_cart')) {
            $this->setData('show_add_to_cart', self::DEFAULT_SHOW_CART);
        }
        return $this->getData('show_add_to_cart');
    }

    /**
     * Get Show Add to WishList Button
     *
     * @return bool
     */
    public function isShowWishList()
    {
        if (!$this->hasData('show_add_to_wishlist')) {
            $this->setData('show_add_to_wishlist', self::DEFAULT_SHOW_WISH_LIST);
        }
        return $this->getData('show_add_to_wishlist');
    }

    /**
     * Get Show Add to Compare Button
     *
     * @return bool
     */
    public function isShowCompare()
    {
        if (!$this->hasData('show_add_to_compare')) {
            $this->setData('show_add_to_compare', self::DEFAULT_SHOW_COMPARE);
        }
        return $this->getData('show_add_to_compare');
    }

    /**
     * Get Show out of Stock Product
     *
     * @return bool
     */
    public function isShowOutOfStock()
    {
        if (!$this->hasData('show_out_of_stock')) {
            $this->setData('show_out_of_stock', self::DEFAULT_SHOW_OUT_OF_STOCK);
        }
        return $this->getData('show_out_of_stock');
    }

    /**
     * Get Show Product List as Slider
     *
     * @return bool
     */
    public function isShowSlider()
    {
        if (!$this->hasData('show_as_slider')) {
            $this->setData('show_as_slider', self::DEFAULT_SHOW_AS_SLIDER);
        }
        return $this->getData('show_as_slider');
    }

    /**
     * Get Number of Products per Slide
     *
     * @return int
     */
    public function isProductsPerSlide()
    {
        if (!$this->hasData('products_per_slide')) {
            $this->setData('products_per_slide', self::DEFAULT_PRODUCTS_PER_SLIDE);
        }
        return $this->getData('products_per_slide');
    }

    /**
     * Get Show the Navigation Dots
     *
     * @return bool
     */
    public function isShowSlideNavigation()
    {
        if (!$this->hasData('show_slider_navigation')) {
            $this->setData('show_slider_navigation', self::DEFAULT_SHOW_NAV_BAR);
        }
        if ($this->getData('show_slider_navigation') == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get Show the Prev/Next Button
     *
     * @return bool
     */
    public function isShowArrows()
    {
        if (!$this->hasData('show_arrows')) {
            $this->setData('show_arrows', self::DEFAULT_SHOW_PREV_NEXT);
        }
        if ($this->getData('show_arrows') == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get Auto Timer
     *
     * @return int
     */
    public function getAutoPlaySlideEvery()
    {
        if (!$this->hasData('auto_every')) {
            $this->setData('auto_every', self::DEFAULT_AUTO_EVERY);
        }
        return $this->getData('auto_every');
    }

    /**
     * Date Range
     *
     * @return string
     */
    public function dateRange()
    {
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();
        $sqlQuery='';
        if ($fromDate !='' && $toDate !='') {
            if (strtotime($toDate) < strtotime($fromDate)) {
                $sqlQuery .=" AND aggregation.period BETWEEN '{$toDate}' AND '{$fromDate}'";
            } else {
                $sqlQuery .=" AND aggregation.period BETWEEN '{$fromDate}' AND '{$toDate}'";
            }
        }
        if ($fromDate !='' && $toDate =='') {
            $sqlQuery .=" AND aggregation.period >= '{$fromDate}'";
        }
        if ($fromDate =='' && $toDate !='') {
            $sqlQuery .=" AND aggregation.period <= '{$toDate}'";
        }
        return $sqlQuery;
    }

    /**
     * Get Page Html Bss
     *
     * @return string
     */
    public function getPagerHtmlBss()
    {
        if ($this->showPager() && $this->checkShowPagerOnSale()) {
            if (!$this->pager) {
                $this->pager = $this->getLayout()->createBlock(
                    \Magento\Catalog\Block\Product\Widget\Html\Pager::class,
                    'widget.products.list.pager.mostview'
                );

                $this->pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName($this->getData('page_var_name'))
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getProductsCount())
                    ->setCollection($this->getProductCollection());
            }
            if ($this->pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
                return $this->pager->toHtml();
            }
        }
        return '';
    }

    /**
     * Check pager OnSale
     * @return bool
     */
    public function checkShowPagerOnSale(): bool
    {
        $showOutOfStock = $this->isShowOutOfStock();
        $collection = $this->productCollectionFactory->create();
        $onSaleCollection = $this->productCollectionFactory->create();
        $collection->setFlag('has_stock_status_filter', true);
        $collection = $this->getStockStatus($collection, $showOutOfStock);
        $collection = $this->getData('getOnSaleCollection')->getOnSaleCollection($collection, $onSaleCollection);
        if ($collection->count() > $this->getProductsPerPage()) {
            return true;
        }
        return false;
    }

    /**
     * Get Stock Status
     *
     * @param $collection
     * @param $showOutOfStock
     * @return mixed
     */
    public function getStockStatus($collection, $showOutOfStock)
    {
        return $this->getData('getStockStatus')->getStockStatus($collection, $showOutOfStock);
    }

    /**
     * Compare Version
     *
     * @param string $version
     * @return bool
     */
    protected function compareVersion($version)
    {
        $versionCurrent =  $this->getData('productMetadata')->getVersion();
        if (version_compare($versionCurrent, $version) === 1 || version_compare($versionCurrent, $version) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Cache Key Info
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $conditions = $this->getData('conditions')
            ? $this->getData('conditions')
            : $this->getData('conditions_encoded');
        if ($this->compareVersion("2.1.0") === true) {
            return [
                'CATALOG_PRODUCTS_LIST_WIDGET',
                $this->getPriceCurrency()->getCurrency()->getCode(),
                $this->_storeManager->getStore()->getId(),
                $this->_design->getDesignTheme()->getId(),
                $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
                (int)$this->getRequest()->getParam($this->getData('page_var_name'), 1),
                $this->getProductsPerPage(),
                $conditions,
                \Magento\Framework\App\ObjectManager::getInstance()
                    ->get(\Magento\Framework\Serialize\Serializer\Json::class)
                    ->serialize($this->getRequest()->getParams())
            ];
        } else {
            return [
                'CATALOG_PRODUCTS_LIST_WIDGET',
                $this->getData('priceCurrencyInterface')->getCurrencySymbol(),
                $this->_storeManager->getStore()->getId(),
                $this->_design->getDesignTheme()->getId(),
                $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
                (int)$this->getRequest()->getParam($this->getData('page_var_name'), 1),
                $this->getProductsPerPage(),
                $conditions,
                $this->getData('serialize')->serialize($this->getRequest()->getParams())
            ];
        }
    }

    /**
     * Get Price Currency
     *
     * @return PriceCurrencyInterface
     */
    public function getPriceCurrency()
    {
        if ($this->priceCurrency === null) {
            $this->priceCurrency = $this->getData('priceCurrencyInterface');
        }
        return $this->priceCurrency;
    }
}
