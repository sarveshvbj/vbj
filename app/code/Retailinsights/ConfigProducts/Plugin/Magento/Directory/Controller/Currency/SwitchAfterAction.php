<?php
namespace Retailinsights\ConfigProducts\Plugin\Magento\Directory\Controller\Currency;

use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Result\PageFactory;

class SwitchAfterAction
{
    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Product
     */
    protected $_product;

    /**
     * @var Item
     */
    protected $quoteFactory;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var Formkey
     */
    protected $formKey;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, Session $customerSession, Cart $cart, CheckoutSession $checkoutSession, ProductFactory $_product, Item $quoteFactory, FormKey $formKey, CartRepositoryInterface $quoteRepository, PageFactory $resultPageFactory)
    {
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->_product = $_product;
        $this->formKey = $formKey;
        $this->quoteFactory = $quoteFactory;
        $this->quoteRepository = $quoteRepository;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function afterExecute(\Magento\Directory\Controller\Currency\SwitchAction $subject)
    {

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/ret_currency_quote.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $currentCurrency = $this
            ->storeManager
            ->getStore()
            ->getCurrentCurrencyCode();
        $newCurrency = $subject->getRequest()
            ->getParam('currency');
        $logger->info('currencyCode ' . $currentCurrency);
        $logger->info('newcurrency ' . $newCurrency);
        // your code here
        $itemId = 0;
        $qty = 0;
        $quoteId = 0;
        $product_detail = array();
        $sessionItems = $this
            ->checkoutSession
            ->getQuote()
            ->getAllItems();
        $quote = $this
            ->checkoutSession
            ->getQuote();

        if ($sessionItems)
        {
             $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
             $storeManager = $_objectManager->get('\Magento\Store\Model\StoreManagerInterface');
             $dataHelper = $_objectManager->get('\Retailinsights\ConfigProducts\Helper\Data');
            $currencyCode = $storeManager->getStore()
                ->getCurrentCurrencyCode();
            $currency = $_objectManager->create('\Magento\Directory\Model\CurrencyFactory')
                ->create()
                ->load($currencyCode);
            $currencySymbol = $currency->getCurrencySymbol();
            foreach ($sessionItems as $session_item)
            {
                $productId = $session_item->getProductId();
                
                $productCollection = $_objectManager->create('Magento\Catalog\Model\Product')
                    ->load($productId);
                $productType = $productCollection->getTypeId();
                if ($productType == 'configurable')
                {
                    if ($session_item->getCustomPriceFlag() == 'true')
                    {
                       $quote_item_id = $session_item->getQuoteId();
                        $cur_code = $dataHelper->updateQuoteData($session_item->getQuoteId() , "true");

                        if ($cur_code['code'] == 'USD')
                        {

                            $custom_price = ($session_item->getCustomPrice() * $cur_code['rate']);
                            $price = $base_price = ($session_item->getCustomPrice() / $cur_code['rate']);
                            $original_cust_price = ($session_item->getOriginalCustomPrice() * $cur_code['rate']);
                            $tax_amount_1 = ($session_item->getCustomPrice() * $session_item->getTaxPercent()) / 100;
                            $tax_amount = $session_item->getQty() * $tax_amount_1;
                            $base_tax_amount = ($tax_amount / $cur_code['rate']);
                            $row_total = ($session_item->getQty() * $custom_price);
                            $base_row_total = ($row_total / $cur_code['rate']);
                            $price_incl_tax = $custom_price + $tax_amount_1;
                            $base_price_incl_tax = $price_incl_tax / $cur_code['rate'];
                            $row_total_incl_tax = $row_total + $tax_amount;
                            $base_row_total_incl_tax = $row_total_incl_tax / $cur_code['rate'];

                            //Setting up Values
                            $session_item->setCustomPrice(number_format($custom_price, 4));
                            $session_item->setPrice(number_format($price, 4));
                            $session_item->setBasePrice(number_format($base_price, 4));
                            $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                            $session_item->setTaxAmount(number_format($tax_amount, 4));
                            $session_item->setBaseTaxAmount(number_format($base_tax_amount, 4));
                            $session_item->setRowTotal(number_format($row_total, 4));
                            $session_item->setBaseRowTotal(number_format($base_row_total, 4));
                            $session_item->setPriceInclTax(number_format($price_incl_tax, 4));
                            $session_item->setBasePriceInclTax(number_format($base_price_incl_tax, 4));
                            $session_item->setRowTotalInclTax(number_format($row_total_incl_tax, 4));
                            $session_item->setBaseRowTotalInclTax(number_format($base_row_total_incl_tax, 4));

                            if($session_item->getCustomFinalPrice()) {
                                 $custom_final_price = ($session_item->getCustomFinalPrice() * $cur_code['rate']);
                                 $session_item->setCustomFinalPrice($custom_final_price);
                            }

                            //setting up quote
                           $res = $dataHelper->updateQuoteTotal($cur_code['code'], $session_item->getQuoteId());
                            $logger->info('After update Quote ' . $res);

                        }
                        else if ($cur_code['code'] == 'INR')
                        {

                            $usd_rate = (1 / $storeManager->getStore()
                                ->getBaseCurrency()
                                ->getRate('USD'));
                            $custom_price = ($session_item->getCustomPrice() * $usd_rate);
                            $price = $base_price = ($session_item->getCustomPrice() / $usd_rate);
                            $original_cust_price = ($session_item->getOriginalCustomPrice() * $usd_rate);
                            $tax_amount_1 = ($session_item->getCustomPrice() * $session_item->getTaxPercent()) / 100;
                            $tax_amount = $session_item->getQty() * $tax_amount_1;
                            $base_tax_amount = ($tax_amount / $usd_rate);
                            $row_total = ($session_item->getQty() * $custom_price);
                            $base_row_total = ($row_total / $usd_rate);
                            $price_incl_tax = $custom_price + $tax_amount_1;
                            $base_price_incl_tax = $price_incl_tax / $usd_rate;
                            $row_total_incl_tax = $row_total + $tax_amount;
                            $base_row_total_incl_tax = $row_total_incl_tax / $usd_rate;

                            //Setting up Values
                            $session_item->setCustomPrice(number_format($custom_price, 4));
                            $session_item->setPrice(number_format($price, 4));
                            $session_item->setBasePrice(number_format($base_price, 4));
                            $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                            $session_item->setTaxAmount(number_format($tax_amount, 4));
                            $session_item->setBaseTaxAmount(number_format($base_tax_amount, 4));
                            $session_item->setRowTotal(number_format($row_total, 4));
                            $session_item->setBaseRowTotal(number_format($base_row_total, 4));
                            $session_item->setPriceInclTax(number_format($price_incl_tax, 4));
                            $session_item->setBasePriceInclTax(number_format($base_price_incl_tax, 4));
                            $session_item->setRowTotalInclTax(number_format($row_total_incl_tax, 4));
                            $session_item->setBaseRowTotalInclTax(number_format($base_row_total_incl_tax, 4));

                            if($session_item->getCustomFinalPrice()) {
                                 $custom_final_price = ($session_item->getCustomFinalPrice() * $usd_rate);
                                 $session_item->setCustomFinalPrice($custom_final_price);
                            }


                            //setting up quote
                           $res = $dataHelper->updateQuoteTotal($cur_code['code'], $session_item->getQuoteId());
                            $logger->info('After update Quote ' . $res);


                        }

                    }

                }
                else if ($productType == 'simple')
                {

                    if ($session_item->getCustomPriceFlag() == 'true')
                    {

                        $cur_code = $dataHelper->updateQuoteData($session_item->getQuoteId() , "true");
                        if ($cur_code['code'] == 'USD')
                        {
                            $custom_price = ($session_item->getCustomPrice() * $cur_code['rate']);
                            $original_cust_price = ($session_item->getOriginalCustomPrice() * $cur_code['rate']);
                            $session_item->setCustomPrice(number_format($custom_price, 4));
                            $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                            if($session_item->getCustomFinalPrice()) {
                                 $custom_final_price = ($session_item->getCustomFinalPrice() * $cur_code['rate']);
                                 $session_item->setCustomFinalPrice($custom_final_price);
                            }
                        }
                        else if ($cur_code['code'] == 'INR')
                        {
                            $usd_rate = (1 / $storeManager->getStore()
                                ->getBaseCurrency()
                                ->getRate('USD'));
                            $custom_price = ($session_item->getCustomPrice() * $usd_rate);
                            $original_cust_price = ($session_item->getOriginalCustomPrice() * $usd_rate);
                            $session_item->setCustomPrice(number_format($custom_price, 4));
                            $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                            if($session_item->getCustomFinalPrice()) {
                                 $custom_final_price = ($session_item->getCustomFinalPrice() * $usd_rate);
                                 $session_item->setCustomFinalPrice($custom_final_price);
                            }

                        }
                    }
                }
            }
            $quote->collectTotals()
            ->save();
         $res = $dataHelper->updateQuoteTotal($currentCurrency, $session_item->getQuoteId());
                            $logger->info('After update Quote ' . $res);
        }
        
        
    }

}

