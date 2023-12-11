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
namespace Webkul\GiftCard\Controller\GiftCard;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Webkul GiftCard Landing page UpdateGiftCard Controller.
 */
class ClearDiscount extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_storeManager;
    protected $_currency;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency,
        \Webkul\GiftCard\Model\GiftUserFactory $giftUser,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\Rule $salesRule,
        \Magento\Checkout\Model\Cart $quote,
        \Magento\Framework\Session\SessionManagerInterface $backendSession,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_currency = $currency;
        $this->_giftuser = $giftUser;
        $this->_customerSession = $customerSession;
        $this->_salesRule = $salesRule;
        $this->_quote = $quote;
        $this->_backendSession = $backendSession;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * GiftCard Landing page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_quote->getQuote()->setGiftVoucherAmt(0)->setGiftVoucherCode('')->collectTotals()->save();

        $collection = $this->_salesRule->getCollection()->load();
        foreach ($collection as $mo) {
            // Delete coupon
            if ($mo->getName() == $this->_backendSession->getCoupancode()) {
                $mo->delete();
                $this->_backendSession->setCoupancode(null);
                $this->_backendSession->setReducedprice(null);
                $this->_quote
                ->getQuote()
                ->setCouponCode(null)
                ->collectTotals()
                ->save();
                $this->_checkoutSession->setCartWasUpdated(true);
            }
        }
        $this->messageManager->addError(__("Gift Card Discount Removed"));

        return $this->_goBack($this->_redirect->getRefererUrl());
    }

    /**
     * Set back redirect url to response
     *
     * @param null|string $backUrl
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _goBack($backUrl = null)
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($backUrl) {
            $resultRedirect->setUrl($backUrl);
        }
        
        return $resultRedirect;
    }
}
