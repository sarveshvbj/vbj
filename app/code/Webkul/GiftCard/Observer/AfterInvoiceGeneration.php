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

namespace Webkul\GiftCard\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
 
class AfterInvoiceGeneration implements ObserverInterface
{
    /** @var \Magento\Framework\Logger\Monolog */
    protected $_logger;

    /** @var Magento\Framework\App\RequestInterface */
    protected $_request;

    /**
     * @param \Psr\Log\LoggerInterface               $loggerInterface
     * @param RequestInterface                       $requestInterface
     */
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        RequestInterface $requestInterface,
        \Magento\Sales\Model\Order $salesOrder,
        \Magento\Catalog\Model\Product $catalpgProduct,
        \Magento\SalesRule\Model\Rule $magentoSalesRule,
        \Webkul\GiftCard\Helper\Data $helperData,
        \Webkul\GiftCard\Model\GiftDetailFactory $giftDetailFactory,
        \Webkul\GiftCard\Model\GiftUserFactory $giftUserFactory
    ) {
        $this->_logger = $loggerInterface;
        $this->_request = $requestInterface;
        $this->_salesOrder = $salesOrder;
        $this->_catalpgProduct = $catalpgProduct;
        $this->_magentoSalesRule = $magentoSalesRule;
        $this->_helperData = $helperData;
        $this->_giftDetailFactory = $giftDetailFactory;
        $this->_giftUserFactory = $giftUserFactory;
    }

/**
 * This is the method that fires when the event runs.
 *
 * @param Observer $observer
 */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $oids = $invoice->getOrderId();
        $sl = $this->_salesOrder->load($oids);

        $couponCode=$sl->getCouponCode();
        $discountAmt=$sl->getDiscountAmount();
        foreach ($invoice->getAllItems() as $item) {
            $productid = $item->getProductId();
            $gcqty=$item->getQty();
            for ($i=0; $i < intval($gcqty); $i++) {
                $giftmodel  = $this->_catalpgProduct->load($productid);
                if ($giftmodel->getTypeId() == 'giftcard') {
                    $useremail = "";
                    foreach ($sl->getAllItems() as $item1) {
                        $options = $item1->getProductOptions();
                    }
                    $customOptions = $options['options'];
                    if (!empty($customOptions)) {
                        foreach ($customOptions as $option) {
                            $useremail = $option['value'];
                        }
                    }
                    $customer=$sl->getCustomerEmail();
                    $customer_name=$sl->getCustomerFirstname()." ".$sl->getCustomerLastname();
                    $mailData=[];
                     
                    /* Assign values for your template variables  */
                    $emailTemplateVariables = [];
     


                    $price= $giftmodel->getPrice();
                    $mailData['price']=$price;
                    $emailTemplateVariables['myvar1'] = $price;
                    $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $_Symbol = $_objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($this->_helperData->getBaseCurrencyCode());

                    $emailTemplateVariables['myvar8'] = $_Symbol->getCurrencySymbol();
                    // $des= $giftmodel->getShortDescription();
                    $des= $giftmodel->getDescription();
                    $mailData['description']=$des;
                    $emailTemplateVariables['myvar2'] = $des;
                    $email = $useremail;
                    $mailData['reciever']=$email;
                    /* Receiver Detail  */
                    $receiverInfo = [
                        'name' => 'Reciver Name',
                        'email' => $email
                    ];
                    $emailTemplateVariables['myvar6'] = 'Reciver Name';
                    $emailTemplateVariables['myvar7'] = $email;
                    $giftcode=$this->_helperData->get_rand_id(12);
                    $mailData['sender']=$customer;
                    $mailData['sender_name']=$customer_name;
                    $emailTemplateVariables['myvar4'] = $customer;
                    $emailTemplateVariables['myvar5'] = $customer_name;
                    /* Sender Detail  */
                    $senderInfo = [
                        'name' => $customer_name,
                        'email' => $customer
                    ];
                    if ($email) {
                        $data=["price"=>$price,"description"=>$des,"email"=>$email,"from"=>$customer];
                        $model=$this->_giftDetailFactory->create()->setData($data);
                        try {
                            $id=$model->save()->getGiftId();
                            $model2=$this->_giftUserFactory->create()->setData(["giftcodeid"=>$id,"amount"=>$price,"alloted"=>date("Y/m/d h:i:sa"),"email"=>$email,"from"=>$customer,"remaining_amt"=>$price]);
                            $id2=$model2->save()->getGiftuserid();
                            $this->_giftDetailFactory->create()->load($id)->setGiftCode($id2.$giftcode)->save();
                            $this->_giftUserFactory->create()->load($id2)->setCode($id2.$giftcode)->save();
                            $emailTemplateVariables['myvar3'] = $id2.$giftcode;
                            $mailData['code']=$id2.$giftcode;
                            try {
                                //Mage::getModel("giftcard/mail")->giftNotifictionMail($mailData);
                                $this->_helperData->customMailSendMethod(
                                    $emailTemplateVariables,
                                    $senderInfo,
                                    $receiverInfo
                                );
                            } catch (\Exception $e) {
                                $e->getMessage();
                            }
                        } catch (\Exception $e) {
                            $e->getMessage();
                        }
                    }
                }
            }
        }
    
        
        $cc=$this->_giftUserFactory->create()->getCollection()->addFieldToFilter('code', $couponCode);
        
        if ($cc->getSize()) {
            $gift_user_data=[];
            $customerName=$sl->getCustomerFirstname()." ".$sl->getCustomerLastname();
            $customerEmail=$sl->getCustomerEmail();
        
            $gift_user_data["orderId"]=$sl->getIncrementId();
            $gift_user_data["reciever_email"]=$customerEmail;
            $gift_user_data["reciever_name"]=$customerName;
            $gift_user_data["reduced_ammount"]=$discountAmt;
            $emailTemplateVariablesForLeftAmt["myvar1"]=$sl->getIncrementId();
            $emailTemplateVariablesForLeftAmt["myvar2"]=$customerEmail;
            $emailTemplateVariablesForLeftAmt["myvar3"]=$customerName;
            $emailTemplateVariablesForLeftAmt["myvar4"]=$discountAmt;
            $emailTemplateVariablesForLeftAmt['myvar8'] = $_Symbol->getCurrencySymbol();
            $model3=$this->_giftUserFactory->create()
            ->getCollection()
            ->addFieldToFilter("code", $couponCode);
            
            foreach ($model3 as $m3) {
                $gift_user_data["previous_ammount"]=$amnt=$m3->getAmount();
                $gift_user_data["gift_code"]=$m3->getCode();
                $emailTemplateVariablesForLeftAmt["myvar5"]=$amnt=$m3->getAmount();
                $emailTemplateVariablesForLeftAmt["myvar6"]=$m3->getCode();
                $m3->setAmount($amnt+$discountAmt)->save();
                $gift_user_data["result_ammount"]=$m3->getAmount();
                $emailTemplateVariablesForLeftAmt["myvar7"]=$m3->getAmount();
            }
            $collection = $this->_magentoSalesRule->getCollection()->load();
            foreach ($collection as $m) {
                if ($m->getName() == $couponCode) {
                    $m->delete();
                }
            }
            $receiverInfo = [
                'name' => $customerName,
                'email' => $customerEmail
            ];
            $senderInfo = [
                'name' => $this->_helperData->getAdminNameFromConfig(),
                'email' => $this->_helperData->getAdminEmailFromConfig()
            ];
            //Mage::getModel("giftcard/mail")->giftAmountMail($gift_user_data);
            $emailTemplateVariablesForLeftAmt['myvar8'] = $this->_helperData->getBaseCurrencyCode();
            $this->_helperData->customMailSendMethodForLeftAmt(
                $emailTemplateVariablesForLeftAmt,
                $senderInfo,
                $receiverInfo
            );
                                                      
            $coupon_model = $this->_magentoSalesRule->getCollection()->load();
            foreach ($coupon_model as $cpn) {
                if (trim($cpn->getName()) == trim($couponCode)) {
                    $cpn->delete();
                }
            }
        }
    }
}
