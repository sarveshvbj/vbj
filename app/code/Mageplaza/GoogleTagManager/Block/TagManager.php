<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_GoogleTagManager
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\GoogleTagManager\Block;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product\CatalogPrice;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Checkout\Model\Cart;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Multishipping\Model\Checkout\Type\Multishipping;
use Magento\Sales\Model\OrderRepository;
use Mageplaza\GoogleTagManager\Helper\Data as HelperData;

/**
 * Class TagManager
 * @package Mageplaza\GoogleTagManager\Block
 */
class TagManager extends Template
{
    /**
     * @var HelperData
     */
    protected $_helper;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Data
     */
    protected $_catalogHelper;

    /**
     * @var ListProduct
     */
    protected $_listProduct;

    /**
     * @var Toolbar
     */
    protected $_toolbar;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var CatalogPrice
     */
    protected $_catalogPrice;

    /**
     * @var Collection|null
     */
    protected $productCollection;

    /**
     * @var Cart
     */
    protected $_cart;

    /**
     * @var Category
     */
    protected $_category;

    /**
     * @var Layer
     */
    protected $_catalogLayer;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var SessionFactory
     */
    protected $customerSession;

    /**
     * TagManager constructor.
     *
     * @param ProductFactory $productFactory
     * @param CategoryFactory $categoryFactory
     * @param Data $catalogHelper
     * @param ListProduct $listProduct
     * @param Toolbar $toolbar
     * @param Context $context
     * @param HelperData $helper
     * @param Registry $registry
     * @param CatalogPrice $catalogPrice
     * @param ObjectManagerInterface $objectManager
     * @param Resolver $layerResolver
     * @param Category $category
     * @param Cart $cart
     * @param TimezoneInterface $timezone
     * @param SessionFactory $customerSession
     * @param ResolverInterface $localeResolver
     * @param OrderRepository $orderRepository
     * @param array $data
     */
    public function __construct(
        ProductFactory $productFactory,
        CategoryFactory $categoryFactory,
        Data $catalogHelper,
        ListProduct $listProduct,
        Toolbar $toolbar,
        Context $context,
        HelperData $helper,
        Registry $registry,
        CatalogPrice $catalogPrice,
        ObjectManagerInterface $objectManager,
        Resolver $layerResolver,
        Category $category,
        Cart $cart,
        TimezoneInterface $timezone,
        SessionFactory $customerSession,
        ResolverInterface $localeResolver,
        OrderRepository $orderRepository,
        array $data = []
    ) {
        $this->_catalogLayer    = $layerResolver->get();
        $this->_catalogHelper   = $catalogHelper;
        $this->_productFactory  = $productFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_helper          = $helper;
        $this->_objectManager   = $objectManager;
        $this->_listProduct     = $listProduct;
        $this->_toolbar         = $toolbar;
        $this->_registry        = $registry;
        $this->_catalogPrice    = $catalogPrice;
        $this->_cart            = $cart;
        $this->_category        = $category;
        $this->timezone         = $timezone;
        $this->customerSession  = $customerSession;
        $this->localeResolver   = $localeResolver;
        $this->orderRepository  = $orderRepository;

        parent::__construct($context, $data);
    }

    /**
     * Get the page limit in category product list page
     *
     * @return int
     */
    public function getPageLimit()
    {
        $result = $this->_toolbar ? $this->_toolbar->getLimit() : 9;

        return (int) $result;
    }

    /**
     * Get the current page number of category product list page
     *
     * @return int|mixed
     */
    public function getPageNumber()
    {
        if ($this->getRequest()->getParam('p')) {
            return $this->getRequest()->getParam('p');
        }

        return 1;
    }

    /**
     * Get AddToCartData data layered from checkout session
     *
     * @return array
     */
    public function getEventData()
    {
        $data = [];

        // event AddToCart in Google Tag Manager
        if ($this->_helper->getSessionManager()->getGTMAddToCartData()) {
            $data['add']['gtm'] = $this->encodeJs($this->_helper->getSessionManager()->getGTMAddToCartData());
        }

        // event RemoveFromCart in Google Tag Manager
        if ($this->_helper->getSessionManager()->getGTMRemoveFromCartData()) {
            $data['remove']['gtm'] = $this->encodeJs($this->_helper->getSessionManager()->getGTMRemoveFromCartData());
        }

        // event AddToCart in Facebook Pixel
        if ($this->_helper->getSessionManager()->getPixelAddToCartData()) {
            $data['add']['pixel'] = $this->encodeJs($this->_helper->getSessionManager()->getPixelAddToCartData());
        }

        //event AddToWishlist in Facebook Pixel
        if ($this->_helper->getSessionManager()->getPixelAddToWishlistData()) {
            $data['wishlist']['pixel']
                = $this->encodeJs($this->_helper->getSessionManager()->getPixelAddToWishlistData());
        }

        // event AddToCart in GA
        if ($this->_helper->getSessionManager()->getGAAddToCartData()) {
            $data['add']['ga'] = $this->encodeJs($this->_helper->getSessionManager()->getGAAddToCartData());
        }

        // event RemoveFromCart in Google Analytics
        if ($this->_helper->getSessionManager()->getGARemoveFromCartData()) {
            $data['remove']['ga'] = $this->encodeJs($this->_helper->getSessionManager()->getGARemoveFromCartData());
        }

        return $data;
    }

    /**
     * Encode JS
     *
     * @param array $data
     *
     * @return string
     */
    public function encodeJs($data)
    {
        return HelperData::jsonEncode($data);
    }

    /**
     * Remove AddToCartData data layered from checkout session
     */
    public function removeAddToCartData()
    {
        $this->_helper->getSessionManager()->setGTMAddToCartData(null);
        $this->_helper->getSessionManager()->setPixelAddToCartData(null);
        $this->_helper->getSessionManager()->setGAAddToCartData(null);
    }

    /**
     * Remove RemoveFromCartData from checkout session
     */
    public function removeRemoveFromCartData()
    {
        $this->_helper->getSessionManager()->setGTMRemoveFromCartData(null);
        $this->_helper->getSessionManager()->setGARemoveFromCartData(null);
    }

    /**
     * Remove AddToWishlist data layered from checkout session
     */
    public function removeAddToWishlistData()
    {
        $this->_helper->getSessionManager()->setPixelAddToWishlistData(null);
    }

    /**
     * decode JS
     *
     * @param string $data
     *
     * @return mixed
     */
    public function decodeJs($data)
    {
        return HelperData::jsonDecode($data);
    }

    /**
     * @return bool
     */
    public function isMultiShipping()
    {
        return $this->_request->getFullActionName() === 'multishipping_checkout_success';
    }

    /**
     * @param Object|null $category
     *
     * @return Collection
     * @throws LocalizedException
     */
    public function getCategotyCollection($category)
    {
        $origCategory = null;
        if ($category) {
            $origCategory = $this->_catalogLayer->getCurrentCategory();
            $this->_catalogLayer->setCurrentCategory($category);
        }

        $collection = clone $this->_catalogLayer->getProductCollection();

        $this->_listProduct->prepareSortableFieldsByCategory($this->_catalogLayer->getCurrentCategory());

        if ($origCategory) {
            $this->_catalogLayer->setCurrentCategory($origCategory);
        }

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getMultiShipping()
    {
        return $this->_helper->getObject(Multishipping::class);
    }

    /**
     * @return Collection
     */
    protected function _getProductCollection()
    {
        if ($this->productCollection === null) {
            $this->productCollection = clone $this->_catalogLayer->getProductCollection();
        }

        return $this->productCollection;
    }

    /**
     * @return mixed
     */
    protected function _getProductAdvancedCollection()
    {
        return $this->_helper
            ->getObject(\Magento\CatalogSearch\Model\Advanced::class)
            ->getProductCollection();
    }
}
