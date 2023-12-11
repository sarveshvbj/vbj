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

namespace Bss\ProductsWidgetSlider\Plugin;

use Bss\ProductsWidgetSlider\Block\GetData;
use Bss\ProductsWidgetSlider\Model\ResourceModel\BestSellerProduct\GetBestSellerCollection;
use Bss\ProductsWidgetSlider\Model\ResourceModel\MostViewProduct\GetMostViewCollection;
use Bss\ProductsWidgetSlider\Model\ResourceModel\OnSaleProduct\GetOnSaleCollection;

/**
 * Class GetData
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SetDataBlock
{
    /**
     * @var \Bss\ProductsWidgetSlider\Helper\Data
     */
    public $bssHelper;
    /**
     * @var \Magento\Wishlist\Helper\Data
     */
    public $wishListHelper;
    /**
     * @var \Magento\Catalog\Helper\Product\Compare
     */
    public $compareHelper;
    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    public $listProduct;
    /**
     * @var \Bss\ProductsWidgetSlider\Model\ResourceModel\GetStockStatus
     */
    public $getStockStatus;
    /**
     * @var \Magento\Catalog\Helper\Output
     */
    public $output;
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    public $productMetadata;
    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    public $postHelper;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    public $priceCurrency;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Serialize
     */
    public $serialize;
    /**
     * @var GetBestSellerCollection
     */
    public $bestSellerCollection;
    /**
     * @var GetMostViewCollection
     */
    public $getMostViewCollection;
    /**
     * @var GetOnSaleCollection
     */
    public $getOnSaleCollection;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * SetDataBlock constructor.
     * @param \Bss\ProductsWidgetSlider\Helper\Data $bssHelper
     * @param \Magento\Wishlist\Helper\Data $wishListHelper
     * @param \Magento\Catalog\Helper\Product\Compare $compareHelper
     * @param \Magento\Catalog\Block\Product\ListProduct $listProduct
     * @param \Bss\ProductsWidgetSlider\Model\ResourceModel\GetStockStatus $getStockStatus
     * @param \Magento\Catalog\Helper\Output $output
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\Data\Helper\PostHelper $postHelper
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\Serialize\Serializer\Serialize $serialize
     * @param GetBestSellerCollection $bestSellerCollection
     * @param GetMostViewCollection $getMostViewCollection
     * @param GetOnSaleCollection $getOnSaleCollection
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        \Bss\ProductsWidgetSlider\Helper\Data $bssHelper,
        \Magento\Wishlist\Helper\Data $wishListHelper,
        \Magento\Catalog\Helper\Product\Compare $compareHelper,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Bss\ProductsWidgetSlider\Model\ResourceModel\GetStockStatus $getStockStatus,
        \Magento\Catalog\Helper\Output $output,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\Data\Helper\PostHelper $postHelper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Serialize\Serializer\Serialize $serialize,
        GetBestSellerCollection $bestSellerCollection,
        GetMostViewCollection $getMostViewCollection,
        GetOnSaleCollection $getOnSaleCollection,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->bssHelper = $bssHelper;
        $this->wishListHelper = $wishListHelper;
        $this->compareHelper = $compareHelper;
        $this->listProduct = $listProduct;
        $this->getStockStatus = $getStockStatus;
        $this->output = $output;
        $this->productMetadata = $productMetadata;
        $this->postHelper = $postHelper;
        $this->priceCurrency = $priceCurrency;
        $this->serialize = $serialize;
        $this->bestSellerCollection = $bestSellerCollection;
        $this->getMostViewCollection = $getMostViewCollection;
        $this->getOnSaleCollection = $getOnSaleCollection;
        $this->productRepository = $productRepository;
    }

    /**
     * Plugin around start construct
     *
     * @param GetData $subject
     * @param $result
     * @return mixed
     */
    public function afterStart($subject, $result)
    {
        $result->setData('bssHelper', $this->bssHelper);
        $result->setData('wishListHelper', $this->wishListHelper);
        $result->setData('compareHelper', $this->compareHelper);
        $result->setData('listProduct', $this->listProduct);
        $result->setData('getStockStatus', $this->getStockStatus);
        $result->setData('Output', $this->output);
        $result->setData('productMetadata', $this->productMetadata);
        $result->setData('postHelper', $this->postHelper);
        $result->setData('priceCurrencyInterface', $this->priceCurrency);
        $result->setData('serialize', $this->serialize);
        $result->setData('getMostViewCollection', $this->getMostViewCollection);
        $result->setData('getBestSellerCollection', $this->bestSellerCollection);
        $result->setData('getOnSaleCollection', $this->getOnSaleCollection);
        $result->setData('productRepository', $this->productRepository);
        return $result;
    }
}
