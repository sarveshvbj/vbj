<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul Software Private Limited
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\GiftCard\Block;

use Magento\Store\Model\Store;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * GiftCatrd block.
 *
 * @author      Webkul Software
 */
class CheckoutGiftCardCoupan extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * Magento\Framework\Registry.
     *
     * @var [type]
     */
    protected $_registry;

    /**
     * Customer session
     *
     * @var \Magento\Framework\App\Http\Context
     */
    protected $_httpContext;

    protected $_checkoutSession;

    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session                  $_customerSession
     * @param \Magento\Framework\Registry                      $_registry
     * @param \Webkul\ShowPriceAfterLogin\Helper\Data          $helper
     * @param DateTime                                         $date
     * @param Store                                            $store
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $_registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Webkul\GiftCard\Helper\Data $helperData,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Checkout\Model\Session $_checkoutSession,
        DateTime $date,
        Store $store,
        array $data = []
    ) {
        $this->_registry = $_registry;
        $this->_helperData = $helperData;
        $this->_objectManager = $objectManager;
        $this->_session = $context->getStoreManager();
        $this->_product = $product;
        $this->_checkoutSession = $_checkoutSession;
        parent::__construct($context, $data);
        $this->_httpContext = $httpContext;
    }

    /**
     * get storeAvilability that weather it is,
     * enable or disable from configuration.
     *
     * @return int
     */
    public function isCustomerLoggrdIn()
    {
        return  $this->_helperData->isCustomerLoggrdIn();
    }

    public function getSessionDataOfCoupon()
    {
        $couponData = ['code'=>"",'price'=>""];
        $couponCode = $this->_session->getCoupancode();
        $reducedPrice = $this->_session->getReducedprice();
        if (isset($couponCode) && $this->_session->getCoupancode()!=null) {
            $couponData['code'] = $this->_session->getCoupancode();
        }
        if (isset($reducedPrice) && $this->_session->getReducedprice()!=null) {
            $couponData['price'] = $this->_session->getReducedprice();
        }
        return $couponData;
    }
    /**
     * get Product
     *
     * @return Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        $id = $this->getRequest()->getParam('id');
        $product = $this->_product->create()->load($id);
        ;
        return $product;
    }
    public function getproductType()
    {
        return $this->getProduct()->getTypeID();
    }

    public function getGiftVoucherCode()
    {
        if($this->_checkoutSession->getQuote()->getGiftVoucherCode()) {
            return $this->_checkoutSession->getQuote()->getGiftVoucherCode();
        } else {
            return null;
        }
    }
}
