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
class UpdateGiftCard extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    protected $_storeManager;
    protected $_currency;
    protected $_date;

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
        \Webkul\GiftCard\Helper\Data $dataHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\Rule $salesRule,
        \Magento\Checkout\Model\Cart $quote,
        \Magento\Framework\Session\SessionManagerInterface $backendSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date
    ) {
    
        $this->_resultPageFactory = $resultPageFactory;
        $this->_storeManager = $storeManager;
        $this->_currency = $currency;
        $this->_giftuser = $giftUser;
        $this->_dataHelper = $dataHelper;
        $this->_customerSession = $customerSession;
        $this->_salesRule = $salesRule;
        $this->_quote = $quote;
        $this->_backendSession = $backendSession;
        $this->_checkoutSession = $checkoutSession;
        $this->_date = $date;
        parent::__construct($context);
    }

    /**
     * GiftCard Landing page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $param=$this->getRequest()->getParams();
        $baseCurrencyCode = $this->_dataHelper->getBaseCurrencyCode();
        $currentCurrencyCode = $this->_dataHelper->getCurrentCurrencyCode();
        $allowedCurrencies = $this->_dataHelper->getAllowedCurrencies();
        $rates = $this->_dataHelper->getCurrentCurrencyRate();
        $param['code'] = $param['gift_card_coupon_code'];
        $removeCode = $param['remove_gift_card'];

        if($removeCode == 0) {
            $collections=$this->_giftuser->create()->getCollection();
            $model=$collections->addFieldToSelect("*")->addFieldToFilter("code", $param["code"])->getFirstItem();

            $expiry_date = $model->getExpiryDate();
            $current_date = $this->_date->date()->format('Y-m-d');

            $usermodel=$collections;
            $acamm=0;
            $gvamt=0;
            
            if ($usermodel->getSize() > 0) {
                foreach ($usermodel as $u) {
                    $acamm=(float)$u->getRemainingAmt();
                    $gvamt=(float)$u->getAmount();
                }
            }

            if ((float)$gvamt>$acamm) {
                $gvamt=$acamm;
            }

            if ((float)$gvamt==0) {

                $collection = $this->_salesRule->getCollection();
                foreach ($collection as $mo) {
                    // Delete coupon
                    if ($mo->getName() == trim($param['code'])) {
                        $mo->delete();
                        $this->_backendSession->setCoupancode(null);
                        $this->_backendSession->setReduceprice(null);
                    }
                }
                $this->messageManager->addError(__("Gift Card '".$param['code']."' is not valid"));
                //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession("Gift Card ".$param['code']." is not valid",'failure');
            } elseif (!empty($expiry_date) && strtotime($current_date) > strtotime($expiry_date)) {
                $this->messageManager->addError(__("Gift Card '".$param['code']."' has expired."));
            } elseif ((float)$gvamt<=$acamm) {
                if (!empty($param['code'])) {
                        $model=$collections->addFieldToFilter("code", trim($param['code']));
                    foreach ($model as $m) {
                        $giftcode=$m->getCode();
                    }
                    if ($giftcode==trim($param['code'])) {
                        $this->_backendSession->setReducedprice((float)$gvamt);
                        $this->_backendSession->setCoupancode(trim($param['code']));
                        $name = trim($param['code']);
                        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
                        $storeId = $this->_storeManager->getStore()->getStoreId();
                        $customerGroupId = [0,1];
                        $actionType = 'cart_fixed';
                        $baseCurrencyCode = $this->_dataHelper->getBaseCurrencyCode();
                        $currentCurrencyCode = $this->_dataHelper->getCurrentCurrencyCode();
                        $allowedCurrencies = $this->_dataHelper->getAllowedCurrencies();
                        $rates = $this->_dataHelper->getCurrentCurrencyRate();
                        $discount = (float)$gvamt*$rates;

                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $disccurrency = $objectManager->create('Magento\Framework\Pricing\Helper\Data')->currency($discount,true,false);

                        $shoppingCartPriceRule = $this->_salesRule;
                        $collection = $this->_salesRule->getCollection();
                        foreach ($collection as $model) {
                            if ($model->getName() == trim($param['code'])) {
                                $model->delete();
                            }
                        }
                        /*$shoppingCartPriceRule
                        ->setName($name)
                        ->setCouponCode($name)
                        ->setDescription('')
                        ->setIsActive(1)
                        ->setWebsiteIds([$websiteId])
                        ->setCustomerGroupIds($customerGroupId)
                        ->setFromDate('')
                        ->setCouponType(2)
                        ->setToDate('')
                        //->setSortOrder('')
                        ->setSortOrder(9999)
                        ->setIsGiftVoucher(1)
                        ->setSimpleAction($actionType)
                        ->setDiscountAmount($discount)
                        ->setStopRulesProcessing(0);
                        try {
                            $shoppingCartPriceRule->save();
                        } catch (\Exception $e) {
                            $this->messageManager->addError(__($e->getMessage()));
                            return;
                        }
                        $this->_quote
                        ->getQuote()
                        ->setCouponCode(trim($param['code']))
                        ->collectTotals()
                        ->save();
                        $this->_checkoutSession->setCartWasUpdated(true);*/
                        $price = $gvamt;
                        $gvamt=$price*$rates;

                        $this->_quote->getQuote()->setGiftVoucherAmt($discount)->setGiftVoucherCode(trim($param['code']))->collectTotals()->save();
                        $this->messageManager->addSuccess(__("Gift Card of ".$disccurrency." applied"));
                        //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession("Gift Card of ".$disccurrency." applied",'sucess');

                        if ($gvamt==0) {
                            $this->_backendSession->setCoupancode(null);
                        }
                    } else {
                        $collection = $this->_salesRule->getCollection()->load();
                        foreach ($collection as $model) {
                            // Delete coupon
                            if ($model->getName() == trim($param['code'])) {
                                $model->delete();
                            }
                        }
                        $this->messageManager->addError(__("Gift Card '".$param['code']."' is not valid"));
                        //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession("Gift Card ".$param['code']." is not valid",'failure');
                    }
                } else {
                    $collection = $this->_salesRule->getCollection()->load();
                    foreach ($collection as $model) {
                        // Delete coupon
                        if ($model->getName() == trim($param['code'])) {
                            $model->delete();
                        }
                    }
                    $this->messageManager->addError(__("Gift Card is required"));
                    //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession("Gift Card ".$param['code']." is required",'failure');
                }
            } else {
                $collection = $this->_salesRule->getCollection()->load();
                foreach ($collection as $mo) {
                    // Delete coupon
                    if ($mo->getName() == trim($param['code'])) {
                            $mo->delete();
                            $this->_backendSession->setCoupancode(null);
                            $this->_backendSession->setReduceprice(null);
                    }
                }
                $this->messageManager->addError(__("Invalid amount"));
                //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession('Invalid amount','failure');
            }
        } else {
            $this->_quote->getQuote()->setGiftVoucherAmt(0)->setGiftVoucherCode('')->collectTotals()->save();

            $collection = $this->_salesRule->getCollection()->load();
            foreach ($collection as $mo) {
                // Delete coupon
                if ($mo->getName() == $this->_backendSession->getCoupancode()) {
                    $mo->delete();
                    $this->_backendSession->setCoupancode(null);
                    $this->_backendSession->setReducedprice(null);
                    /*$this->_quote
                    ->getQuote()
                    ->setCouponCode(null)
                    ->collectTotals()
                    ->save();
                    $this->_checkoutSession->setCartWasUpdated(true);*/
                }
            }
            $this->messageManager->addError(__("Gift Card was removed"));
            //$this->_objectManager->create('Incom\Jetmiles\Helper\Customsession')->setCustomSession('Gift Card was removed','failure');
        }

        return $this->_goBack($this->_redirect->getRefererUrl());
    }

    /*public function execute()
    {
            $param=$this->getRequest()->getParams();
            $baseCurrencyCode = $this->_dataHelper->getBaseCurrencyCode();
            $currentCurrencyCode = $this->_dataHelper->getCurrentCurrencyCode();
            $allowedCurrencies = $this->_dataHelper->getAllowedCurrencies();
            $rates = $this->_dataHelper->getCurrentCurrencyRate();
            $price = $param['amount'];
            $price=$price/$rates;
            $param['amount']=$price;

        if ((real)$param['amount']>0) {

            $whom="";

            $collections=$this->_giftuser->create()->getCollection();
            $model=$collections->addFieldToFilter("code", $param["code"]);

            foreach ($model as $m) {
                $whom=$m->getEmail();
            }
            $customerEmail=$this->_customerSession->getCustomer()->getEmail();
            // if($whom!=$customerEmail) {
            //         $collection = Mage::getModel('salesrule/rule')->getCollection()->load();
            //         foreach($collection as $mo) {
            //                 // Delete coupon
            //             if ($mo->getName() == $session->getData("couponcode") )  {
            //                 $mo->delete();
            //                 $session->unsetData("couponcode");
            //                 $session->unsetData("reducedprice");
            //             }
            //         }
            //         echo Mage::helper('giftcard')->__("Gift code is incorrect or you are not authorized to use this gift card,please check the code !!");
            //         exit(0);
            // }
            // $usermodel=$collections->addFieldToFilter("email", $customerEmail)->addFieldToFilter("code", trim($param['code']));
            $usermodel=$collections;
            $acamm=0;
            if ($usermodel->getSize() > 0) {
                foreach ($usermodel as $u) {
                    $acamm=(real)$u->getRemainingAmt();
                }
            }
            if ((real)$param['amount']>$acamm) {
                $param['amount']=$acamm;
            }

            if ((real)$param['amount']==0) {
                $collection = $this->_salesRule->getCollection();
                foreach ($collection as $mo) {
                    // Delete coupon
                    if ($mo->getName() == trim($param['code'])) {
                        $mo->delete();
                        $this->_backendSession->setCoupancode(null);
                        $this->_backendSession->setReduceprice(null);
                        // $session->unsetData("couponcode");
                        // $session->unsetData("reducedprice");
                    }
                }
                $this->messageManager->addError(__("Code is expired."));
            } elseif ((real)$param['amount']<=$acamm) {
                if (!empty($param['code'])) {
                        $model=$collections->addFieldToFilter("code", trim($param['code']));
                    foreach ($model as $m) {
                        $giftcode=$m->getCode();
                    }
                    if ($giftcode==trim($param['code'])) {
                        $this->_backendSession->setReducedprice((real)$param['amount']);
                        $this->_backendSession->setCoupancode(trim($param['code']));
                        // $session->setData("reducedprice",min((real)$param['amount'],(real)$grandTotal));
                        // $session->setData("couponcode",trim($param['code']));
                        $name = trim($param['code']);
                        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
                        $storeId = $this->_storeManager->getStore()->getStoreId();
                        $customerGroupId = [0,1];
                        $actionType = 'cart_fixed';
                        $baseCurrencyCode = $this->_dataHelper->getBaseCurrencyCode();
                        $currentCurrencyCode = $this->_dataHelper->getCurrentCurrencyCode();
                        $allowedCurrencies = $this->_dataHelper->getAllowedCurrencies();
                        $rates = $this->_dataHelper->getCurrentCurrencyRate();
                        $discount = (real)$param['amount']*$rates;
                        $shoppingCartPriceRule = $this->_salesRule;
                        $collection = $this->_salesRule->getCollection();
                        foreach ($collection as $model) {
                            if ($model->getName() == trim($param['code'])) {
                                $model->delete();
                            }
                        }
                        $shoppingCartPriceRule
                        ->setName($name)
                        ->setCouponCode($name)
                        ->setDescription('')
                        ->setIsActive(1)
                        ->setWebsiteIds([$websiteId])
                        ->setCustomerGroupIds($customerGroupId)
                        ->setFromDate('')
                        ->setCouponType(2)
                        ->setToDate('')
                        ->setSortOrder('')
                        ->setSimpleAction($actionType)
                        ->setDiscountAmount($discount)
                        ->setStopRulesProcessing(0);
                        try {
                            $shoppingCartPriceRule->save();
                        } catch (\Exception $e) {
                            $this->messageManager->addError(__($e->getMessage()));
                            return;
                        }
                        $this->_quote
                        ->getQuote()
                        ->setCouponCode(trim($param['code']))
                        ->collectTotals()
                        ->save();
                        $this->_checkoutSession->setCartWasUpdated(true);
                        // Mage::getSingleton("checkout/session")->setCartWasUpdated(true);
                        $price = $param['amount'];
                        $param['amount']=$price*$rates;
                        $this->messageManager->addSuccess(__('Gift Card Discount Applied Successfully'));
                        // $this->messageManager->addSuccess(__("Gift Card discount applied On %1 and Amount %2", trim($param['code']), $param['amount']));
                        // echo "\n";
                        // echo Mage::helper('giftcard')->__("Code :").trim($param['code']);
                        // echo "\n";
                        // $price = $param['amount'];
                        // $param['amount']=$price*$rates;
                                    
                        // echo Mage::helper('giftcard')->__("and Amount :").$param['amount'];

                        if ($param['amount']==0) {
                            $this->_backendSession->setCoupancode(null);
                        }
                        // $session->unsetData("couponcode");
                    } else {
                        $collection = $this->_salesRule->getCollection()->load();
                        foreach ($collection as $model) {
                            // Delete coupon
                            if ($model->getName() == trim($param['code'])) {
                                $model->delete();
                            }
                        }
                        $this->messageManager->addError(__("code is incorrect"));
                    }
                } else {
                    $collection = $this->_salesRule->getCollection()->load();
                    foreach ($collection as $model) {
                        // Delete coupon
                        if ($model->getName() == trim($param['code'])) {
                            $model->delete();
                        }
                    }
                    $this->messageManager->addError(__("code is required"));
                }
            } else {
                $collection = $this->_salesRule->getCollection()->load();
                foreach ($collection as $mo) {
                    // Delete coupon
                    if ($mo->getName() == trim($param['code'])) {
                            $mo->delete();
                            $this->_backendSession->setCoupancode(null);
                            $this->_backendSession->setReduceprice(null);
                            // $session->unsetData("couponcode");
                            // $session->unsetData("reducedprice");
                    }
                }
                $this->messageManager->addError(__("Please enter a valid amount"));
            }
        } else {
            $collection = $this->_salesRule->getCollection()->load();
            foreach ($collection as $mo) {
                // Delete coupon
                if ($mo->getName() == trim($param['code'])) {
                    $mo->delete();
                    $this->_backendSession->setCoupancode(null);
                    $this->_backendSession->setReduceprice(null);
                    // $session->unsetData("couponcode");
                    // $session->unsetData("reducedprice");
                }
            }
            $this->messageManager->addError(__("Please enter a valid amount"));
        }
    }*/

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
