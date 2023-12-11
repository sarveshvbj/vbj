<?php
/**
 * Webkul Software.
 *
 * @category   Webkul
 * @package    Webkul_StoreOptimization
 * @author     Webkul
 * @copyright  Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license    https://store.webkul.com/license.html
 */

namespace Webkul\StoreOptimization\Block;

class InfiniteScroller extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Webkul\StoreOptimization\Helper\Data
     */
    protected $_helper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\StoreOptimization\Helper\Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * get current toolbar from block
     *
     * @return Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function getCurrentPager()
    {
        return $this->getLayout()->getBlock('product_list_toolbar_pager');
    }

    /**
     * can show insection observer on the page
     *
     * @return boolean
     */
    public function canDisplay()
    {
        return $this->_helper->getIsScrollerEnable();
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->canDisplay()) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * is lazyload enbled
     *
     * @return boolean
     */
    public function isLazyLoadEnabled()
    {
        return $this->_helper->getIsLazyLoadingEnable();
    }
}
