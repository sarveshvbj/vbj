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

class SwitchAction
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

    public function beforeExecute(\Magento\Directory\Controller\Currency\SwitchAction $subject)
    {
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        //$newCurrency = $subject->getRequest()->getParam('currency');
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/beforeswitchlog.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('current ' . $currentCurrency);*/
        //  $logger->info('newcurrency '.$newCurrency);
        // your code here
        $itemId = 0;
        $qty = 0;
        $quoteId = 0;
        $product_detail = array();
        $quote = $this->checkoutSession->getQuote();
        $sessionItems = $quote->getAllItems();

        if ($sessionItems)
        {

               foreach ($sessionItems as $session_item)
            {
              /*$logger->info('sessionitem '.$session_item->getItemId());*/
              $session_item->getProduct()->setIsSuperMode(false);  
            }
           $quote->save();


         }
        
    }

}

