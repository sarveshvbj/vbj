<?php
namespace Retailinsights\ConfigProducts\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Retailinsights\ConfigProducts\Helper\Data as RetailHelper;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\StoreManagerInterface as StoreManager;

class ValidateCartObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
                    protected $messageManager;

    /**
     * @var RedirectInterface
     */
                    protected $redirect;

    /**
     * @var Cart
     */
                    protected $cart;

    /**
     * @var Data Helper
     */
                    protected $retailHelper;

    /**
     * @var Data Helper
     */
                    protected $storeManager;

    /**
     * @param ManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param CustomerCart $cart
     */
                    public function __construct(
        ManagerInterface $messageManager,
        RedirectInterface $redirect,
        CustomerCart $cart,
        RetailHelper $retailHelper,
        StoreManager $storeManager
    )
    {
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->cart = $cart;
        $this->retailHelper = $retailHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * Validate Cart Before going to checkout
     * - event: controller_action_predispatch_checkout_index_index
     *
     * @param Observer $observer
     * @return void
     */
                    public function execute(Observer $observer)
    {
        $quote = $this->cart->getQuote();
        $controller = $observer->getControllerAction();
        $cartItemsQty = $quote->getItemsQty();
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/beforecheckout.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);*/
        $currentCurrency = $this->storeManager->getStore()
            ->getCurrentCurrencyCode();
        /*$logger->info('current currencyCode ' . $currentCurrency);*/
        $validCheck = true;
        $product_name = "";
        $product_sku = "";
        $quote_id = 0;
        $sessionItems = $quote->getAllItems();

        if ($sessionItems) {
            /*$logger->info('Inside Session Item');*/
            foreach ($sessionItems as $session_item) {
                /*$logger->info('Inside For each Item');*/
                if ($session_item->getCustomPriceFlag() == 'true') {
                    /*$logger->info('Inside custom price');*/
                    $config_id = $session_item->getConfigId();
                    $sku = $session_item->getProductSku();
                    $purity_value = $session_item->getPurityValue();
                    $ring_value = $session_item->getRingValue();
                    $diamond_value = $session_item->getDiamondValue();
                    $val = $this->retailHelper->checkValidProduct($config_id, $sku, $ring_value, $purity_value, $diamond_value);
                    if ($val['status'] == 'success') {
                        $final_price = floatval($val['response']['final_price']);
                        $spl_price = floatval($val['response']['special_price']);
                        $config_id = $val['response']['id'];
                        if (round($spl_price) != 0) {
                            $final_price = $spl_price;
                        }
                        /*$logger->info('Final Price ' . $final_price);*/

                        if ($session_item->getProductType() == 'configurable') {
                            if ($final_price != $session_item->getbasePrice() && (empty($_COOKIE['cookie_name']) || $_COOKIE['cookie_name'] != 'nonoffer')) {
                                $product_name .= $session_item->getName() . " ";
                                $product_sku .= $session_item->getSku() . " ";
                                $quote_id = $session_item->getQuoteId();
                                $cur_code = $this->retailHelper->updateQuoteData($session_item->getQuoteId(), "true");

                                if ($cur_code['code'] == 'USD') {
                                    $custom_price = ($final_price * $cur_code['rate']);
                                    $price = $base_price = ($final_price / $cur_code['rate']);
                                    $original_cust_price = ($final_price * $cur_code['rate']);
                                    $tax_amount_1 = ($final_price * $session_item->getTaxPercent()) / 100;
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

                            //setting up quote
                                    $res = $this->retailHelper->updateQuoteTotal($cur_code['code'], $session_item->getQuoteId());
                                } else if ($cur_code['code'] == 'INR') {
                                    $usd_rate = (1 / $this->storeManager->getStore()
                                        ->getBaseCurrency()
                                        ->getRate('USD'));
                                    $custom_price = ($final_price);
                                    $price = $base_price = ($final_price);
                                    $original_cust_price = ($final_price);
                                    $tax_amount_1 = ($final_price * $session_item->getTaxPercent()) / 100;
                                    $tax_amount = $session_item->getQty() * $tax_amount_1;
                                    $base_tax_amount = ($tax_amount);
                                    $row_total = ($session_item->getQty() * $custom_price);
                                    $base_row_total = ($row_total);
                                    $price_incl_tax = $custom_price + $tax_amount_1;
                                    $base_price_incl_tax = $price_incl_tax;
                                    $row_total_incl_tax = $row_total + $tax_amount;
                                    $base_row_total_incl_tax = $row_total_incl_tax;

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

                            //setting up quote
                                    $res = $this->retailHelper->updateQuoteTotal($cur_code['code'], $session_item->getQuoteId());
                                    /*$logger->info('After update Quote ' . $res);*/
                                }
                            }
                        } else if ($session_item->getProductType() == 'virtual') {
                            $usd_rate = $this->storeManager->getStore()
                                ->getBaseCurrency()
                                ->getRate('USD');
                            if ($currentCurrency == 'USD') {
                                $current_custom_price = number_format(($session_item->getCustomPrice() * $usd_rate), 4);
                            } else if ($currentCurrency == 'INR') {
                                $current_custom_price = $session_item->getCustomPrice();
                            }

                            if ($final_price != $current_custom_price) {

                               // $validCheck = false;
                                $product_name .= $session_item->getName() . " ";
                                $product_sku .= $session_item->getSku() . " ";
                                $quote_item_id = $session_item->getQuoteId();
                                $cur_code = $this->retailHelper->updateQuoteData($session_item->getQuoteId(), "true");
                                if ($cur_code['code'] == 'USD') {
                                    $custom_price = ($final_price * $cur_code['rate']);
                                    $original_cust_price = ($final_price * $cur_code['rate']);
                                    $session_item->setCustomPrice(number_format($custom_price, 4));
                                    $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                                } else if ($cur_code['code'] == 'INR') {
                                    $usd_rate = (1 / $this->storeManager->getStore()
                                        ->getBaseCurrency()
                                        ->getRate('USD'));
                                    $custom_price = ($final_price);
                                    $original_cust_price = ($final_price);
                                    $session_item->setCustomPrice(number_format($custom_price, 4));
                                    $session_item->setOriginalCustomPrice(number_format($original_cust_price, 4));
                                }
                            }
                        }
                    } else {
                        $validCheck = false;
                        $product_name .= $session_item->getName() . " ";
                        $product_sku .= $session_item->getSku() . " ";
                        /*$logger->info('error');*/
                    }
                }

                $quote->collectTotals()
                    ->save();
                if ($quote_id != 0) $res = $this->retailHelper->updateQuoteTotal($currentCurrency, $quote_id);
            }
        }
        if (!($validCheck)) {
            if ($product_name != "") {
                $this->messageManager->addNoticeMessage(__("Product doesn't exist our system OR Price was Changed. Please remove '" . $product_name . "' and add again"));
            } else {
                $this->messageManager->addNoticeMessage(
                    __("Product doesn't exist our system OR Price was Changed. Please remove cart and add again")
                );
            }

            $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
        }
    }
}
