<?php
namespace Vaibhav\Retail\Plugin\Magento\Directory\Controller\Currency;

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
        $currentCurrency = $this
            ->storeManager
            ->getStore()
            ->getCurrentCurrencyCode();
        //$newCurrency = $subject->getRequest()->getParam('currency');
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/afterSwitchlog.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('current ' . $currentCurrency);*/
        //  $logger->info('newcurrency '.$newCurrency);
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
                if ($productType == 'simple')
                {
                    $productPriceById = $productCollection->getPrice();
                    $productSpecialPriceById = $productCollection->getSpecialPrice();
                    /*$logger->info('Price ' . $productPriceById);
                    $logger->info('SpecialPrice ' . $productSpecialPriceById);
                    $logger->info('sessionitem ' . $session_item->getItemId());*/
                    $offer_percentage = $productCollection->getResource()
                        ->getAttribute('smart_percentage')
                        ->getFrontend()
                        ->getValue($productCollection);
                    $discount_diamond_in = $productCollection->getResource()
                        ->getAttribute('discount_diamond_in')
                        ->getFrontend()
                        ->getValue($productCollection);
                    $metal_discount_in = $productCollection->getResource()
                        ->getAttribute('metal_discount_in')
                        ->getFrontend()
                        ->getValue($productCollection);
                    $discount_making_charge_in = $productCollection->getResource()
                        ->getAttribute('discount_making_charge_in')
                        ->getFrontend()
                        ->getValue($productCollection);
                    $gst = (int)($productCollection->getResource()
                        ->getAttribute('tax_amount')
                        ->getFrontend()
                        ->getValue($productCollection));
                    $finalPrice = round($productPriceById);
                    if (isset($offer_percentage) && $offer_percentage != '' && $_COOKIE['cookie_name'] == 'nonoffer')
                    {
                        echo 'Smart By Product' . '<br>';
                        if ($currencyCode == 'USD' && $currencySymbol == '$')
                        {
                            $customPricess = round($finalPrice - ($finalPrice * $offer_percentage) / 100);
                            $customPrice = round(($customPricess) * 0.014);
                            //$customPrice = round($customPrices + ($customPrices * 5.5) / 100);
                        }
                        else
                        {
                            $customPrice = round($finalPrice - ($finalPrice * $offer_percentage) / 100);
                        }
                    }
                    elseif (isset($offer_percentage) && $offer_percentage != '' && $_COOKIE['cookie_name'] == 'null')
                    {
                        echo 'non By Product' . '<br>';
                        if ($currencyCode == 'USD' && $currencySymbol == '$')
                        {
                            $customPrice = round(($finalPrice) * 0.014);
                            //$customPrice = round($customsPrice + ($customsPrice * 5.5) / 100);
                        }
                        else
                        {
                            $customPrice = $finalPrice;
                        }
                    }
                    elseif (isset($discount_diamond_in) || isset($discount_making_charge_in) || isset($metal_discount_in) || $discount_making_charge_in != '' || $discount_diamond_in != '' || $metal_discount_in != '')
                    {
                        echo 'offer Product' . '<br>';
                        if (isset($discount_diamond_in))
                        {
                            $dis_per = $discount_diamond_in;
                        }
                        elseif (isset($discount_making_charge_in))
                        {
                            $dis_per = $discount_making_charge_in;
                        }
                        elseif (isset($metal_discount_in))
                        {
                            $dis_per = $metal_discount_in;
                        }
                        if ($currencyCode == 'USD' && $currencySymbol == '$')
                        {
                            $customPricess = $productSpecialPriceById; //round($finalPrice - ($finalPrice * $dis_per)/100);
                            $customPrice = round(($customPricess) * 0.014);
                            //$customPrice = round($customPrices + ($customPrices * 5.5) / 100);
                        }
                        else
                        {
                            $customPrice = $productSpecialPriceById; //round($finalPrice - ($finalPrice * $dis_per)/100);
                            
                        }
                    }
                    else
                    {
                        echo 'simple Product';
                        if ($currencyCode == 'USD' && $currencySymbol == '$')
                        {
                            if (isset($productSpecialPriceById))
                            {
                                $customPrice = round(($productSpecialPriceById) * 0.014);
                                //$customPrice = round($customsPrice + ($customsPrice * 5.5) / 100);
                            }
                            else
                            {
                                $customPrice = round(($finalPrice) * 0.014);
                                //$customPrice = round($customsPrice + ($customsPrice * 5.5) / 100);
                            }
                        }
                        else
                        {
                            if (isset($productSpecialPriceById))
                            {
                                $customPrice = $productSpecialPriceById;
                            }
                            else
                            {
                                $customPrice = $finalPrice;
                            }
                        }
                    }

            $price = $customPrice; 
            $session_item->setCustomPrice($price);
            $session_item->setOriginalCustomPrice($price);
            $session_item->getProduct()->setIsSuperMode(true);

                    //$session_item->getProduct()->setIsSuperMode(true);

                }
                else if($productType == 'configurable') {
          
        //            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/currency_quote.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);
        //  $logger->info('type ' . $productType);
        // $logger->info('custom_price ' . $session_item->getCustomPrice());
        //  $logger->info('base_price ' . $session_item->getBasePrice());
        //  $logger->info('item_id' . $session_item->getItemID());
        //  $logger->info('rowtotal' . $session_item->getRowTotal());

                } else if($productType == 'virtual'){
        //               $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/currency_quote.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);
        //  $logger->info('type ' . $productType);
        // $logger->info('custom_price ' . $session_item->getCustomPrice());
        //  $logger->info('base_price ' . $session_item->getBasePrice());
        //  $logger->info('item_id' . $session_item->getItemID());
        //  $logger->info('rowtotal' . $session_item->getRowTotal());
                }
            }
                $quote->save();

            }

        }

    }
    
