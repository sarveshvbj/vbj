<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_GiftCard
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\GiftCard\Helper;

use Magento\Framework\UrlInterface;

/**
 * Custom GiftCard Data helper.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE_FIELD  = 'giftcard/emaill/gift_notification_mail';

    const XML_PATH_GIFT_LEFT_AMT_EMAIL_TEMPLATE_FIELD  = 'giftcard/email2/admin_amt_notification_mail';

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        /*\Iksula\EmailTemplate\Helper\Email $email,*/
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Webkul\GiftCard\Model\GiftDetail $giftcard,
        \Webkul\GiftCard\Model\GiftUser $giftuser
    ) {
       /* $this->email = $email;*/
        $this->_storeManager = $storeManager;
        $this->_currency = $currency;
        $this->_customerSession = $customerSession;
        $this->_scopeConfig = $context;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->giftcard = $giftcard;
        $this->giftuser = $giftuser;
        parent::__construct($context);
    }

    public function deleteGiftVoucher($id){
        $response = array();
        try {
            $giftcard = $this->giftcard->load($id);
            $giftcard->delete();

            $giftuser = $this->giftuser->load($id,'giftcodeid');
            $giftuser->delete();

            $response['is_error'] = 0;
            $response['message'] = 'Gift voucher successfully deleted';
            $response['return_url'] = '*/*/index';
        } catch (\Exception $e) {
            $response['is_error'] = 1;
            $response['message'] = $e->getMessage();
            $response['return_url'] = '*/*/index';
        }
        
        return $response;
    }

    public function saveGiftVoucher($form){
        $response = array();
        $validation = false;

        $dateFormat = date('Y-m-d', strtotime($form['expiry_date']));

        if(strlen($form['amount']) && strlen($form['email']) && strlen($form['code'])){
            $validation = true;
        }

        if(!$validation){

            $response['is_error'] = 1;
            $response['message'] = 'Provide valid details for git voucher';
            $response['return_url'] = '*/*/form';
            return $response;

        }
        
        if(isset($form['giftuserid']) && isset($form['giftcodeid'])){
            if(strlen($form['giftuserid']) && strlen($form['giftcodeid'])){

                $this->giftcard->setData(array(
                    'gift_id'=>$form['giftcodeid'],
                    'price'=>$form['amount'],
                    'email'=>$form['email'],
                    'gift_code'=>$form['code'],
                    'expiry_date'=>$form['expiry_date'],
                    'used'=>$form['used'],
                    'from'=>$form['email']));
                $this->giftcard->save();

                $this->giftuser->setData(array(
                    'giftuserid'=>$form['giftuserid'],
                    'giftcodeid'=>$form['giftcodeid'],
                    'code'=>$form['code'],
                    'remaining_amt'=>$form['remaining_amt'],
                    'amount'=>$form['amount'],
                    'email'=>$form['email'],
                    'from'=>$form['email']));
                $this->giftuser->save();
                

                $response['is_error'] = 0;
                $response['message'] = 'Updated new gift voucher successfully';
                $response['return_url'] = '*/*/index';
                return $response;
            }
        }

        $this->giftcard->setData(array(
            'price'=>$form['amount'],
            'email'=>$form['email'],
            'gift_code'=>$form['code'],
            'expiry_date'=>$form['expiry_date'],
            'from'=>$form['email']));
        $this->giftcard->save();

        $giftcard_id = $this->giftcard->getId();

        $this->giftuser->setData(array('giftcodeid'=>$giftcard_id,
            'code'=>$form['code'],
            'amount'=>$form['amount'],
            'alloted'=>date('Y-m-d H:i:s'),
            'email'=>$form['email'],
            'remaining_amt'=>$form['remaining_amt'],
            'from'=>$form['email']));

        $this->giftuser->save();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of Object Manager
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); // Instance of Pricing Helper
        $formattedPrice = $priceHelper->currency($form['amount'], true, false);

        // SEND EMAIL 
        $data = array(
            'coupon' => $form['code'],
            'amount' => $formattedPrice,
            'name' => $form['email'],
            'expiry_date' => date("jS F Y", strtotime($dateFormat))
        );

        $result['email'] =  $this->scopeConfig->getValue('trans_email/ident_general/email');
        $result['name'] = $this->scopeConfig->getValue('trans_email/ident_general/name');

        $receiver['email'] = $form['email'];
        $receiver['name'] = '';

        //$this->email->emailTemplate('gift_voucher',$data,$result,$receiver);

        // END EMAIL


        $response['is_error'] = 0;
        $response['message'] = 'Added new gift voucher successfully';
        $response['return_url'] = '*/*/index';



        return $response;

    }

    public function isCustomerLoggrdIn()
    {
        return $this->_customerSession->isLoggedIn();
    }
    
    public function getBaseCurrencyCode()
    {
        return $this->_storeManager->getStore()->getBaseCurrencyCode();
    }
    
     /**
      * Get current store currency code
      *
      * @return string
      */
    public function getCurrentCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyCode();
    }
    
    /**
     * Get default store currency code
     *
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
        return $this->_storeManager->getStore()->getDefaultCurrencyCode();
    }
    
    /**
     * Get allowed store currency codes
     *
     * If base currency is not allowed in current website config scope,
     * then it can be disabled with $skipBaseNotAllowed
     *
     * @param bool $skipBaseNotAllowed
     * @return array
     */
    public function getAvailableCurrencyCodes($skipBaseNotAllowed = false)
    {
        return $this->_storeManager->getStore()->getAvailableCurrencyCodes($skipBaseNotAllowed);
    }
    
    /**
     * Get array of installed currencies for the scope
     *
     * @return array
     */
    public function getAllowedCurrencies()
    {
        return $this->_storeManager->getStore()->getAllowedCurrencies();
    }
    
    /**
     * Get current currency rate
     *
     * @return float
     */
    public function getCurrentCurrencyRate()
    {
        return $this->_storeManager->getStore()->getCurrentCurrencyRate();
    }
    
    /**
     * Get currency symbol for current locale and currency code
     *
     * @return string
     */
    public function getCurrentCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }
    public function get_rand_id($length)
    {
        if ($length>0) {
            $rand_id="";
            for ($i=1; $i<=$length; $i++) {
                    mt_srand((double)microtime() * 1000000);
                    $num = mt_rand(1, 36);
                    $rand_id .= $this->assign_rand_value($num);
            }
        }
        return $rand_id;
    }

    public function assign_rand_value($num)
    {
        // accepts 1 - 36
        switch ($num) {
            case "1":
                $rand_value = "A";
                break;
            case "2":
                $rand_value = "B";
                break;
            case "3":
                $rand_value = "C";
                break;
            case "4":
                $rand_value = "D";
                break;
            case "5":
                $rand_value = "E";
                break;
            case "6":
                $rand_value = "F";
                break;
            case "7":
                $rand_value = "G";
                break;
            case "8":
                $rand_value = "H";
                break;
            case "9":
                $rand_value = "I";
                break;
            case "10":
                $rand_value = "J";
                break;
            case "11":
                $rand_value = "K";
                break;
            case "12":
                $rand_value = "L";
                break;
            case "13":
                $rand_value = "M";
                break;
            case "14":
                $rand_value = "N";
                break;
            case "15":
                $rand_value = "O";
                break;
            case "16":
                $rand_value = "P";
                break;
            case "17":
                $rand_value = "Q";
                break;
            case "18":
                $rand_value = "R";
                break;
            case "19":
                $rand_value = "S";
                break;
            case "20":
                $rand_value = "T";
                break;
            case "21":
                $rand_value = "U";
                break;
            case "22":
                $rand_value = "V";
                break;
            case "23":
                $rand_value = "W";
                break;
            case "24":
                $rand_value = "X";
                break;
            case "25":
                $rand_value = "Y";
                break;
            case "26":
                $rand_value = "Z";
                break;
            case "27":
                $rand_value = "0";
                break;
            case "28":
                $rand_value = "1";
                break;
            case "29":
                $rand_value = "2";
                break;
            case "30":
                $rand_value = "3";
                break;
            case "31":
                $rand_value = "4";
                break;
            case "32":
                $rand_value = "5";
                break;
            case "33":
                $rand_value = "6";
                break;
            case "34":
                $rand_value = "7";
                break;
            case "35":
                $rand_value = "8";
                break;
            case "36":
                $rand_value = "9";
                break;
        }
        return $rand_value;
    }

    public function getAdminNameFromConfig()
    {
            return $this->scopeConfig
                        ->getValue(
                            'giftcard/emaill/gift_admin_mail_name',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        );
    }

    public function getAdminEmailFromConfig()
    {
            return $this->scopeConfig
                        ->getValue(
                            'giftcard/emaill/gift_admin_mail_email',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        );
    }

    protected function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        // return $this->scopeConfig
        //     ->getValue(
        //         'giftcard/emaill/gift_notification_mail',
        //         \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        //     );
    }
 
    /**
     * Return store
     *
     * @return Store
     */
    // public function getStore()
    // {
    //     return $this->_storeManager->getStore();
    // }

    public function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath, $this->_storeManager->getStore()->getStoreId());
    }
 
    /**
     * [generateTemplate description]  with template file and tempaltes variables values
     * @param  Mixed $emailTemplateVariables
     * @param  Mixed $senderInfo
     * @param  Mixed $receiverInfo
     * @return void
     */
    public function generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo)
    {
        $template =  $this->_transportBuilder->setTemplateIdentifier($this->temp_id)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, /* here you can defile area and
                                                                                 store of template for which you prepare it */
                        'store' => $this->_storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars($emailTemplateVariables)
                ->setFrom($senderInfo)
                ->addTo($receiverInfo['email'], $receiverInfo['name']);
        return $this;
    }
 
    /**
     * [sendInvoicedOrderEmail description]
     * @param  Mixed $emailTemplateVariables
     * @param  Mixed $senderInfo
     * @param  Mixed $receiverInfo
     * @return void
     */
    /* Send mail method of Gift Card from Admin to Receiver*/
    public function customMailSendMethod($emailTemplateVariables, $senderInfo, $receiverInfo)
    {
        $this->temp_id = $this->getTemplateId(self::XML_PATH_EMAIL_TEMPLATE_FIELD);
        $this->inlineTranslation->suspend();
        $this->generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo);
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
 
     /**
      * [sendInvoicedOrderEmail description]
      * @param  Mixed $emailTemplateVariables
      * @param  Mixed $senderInfo
      * @param  Mixed $receiverInfo
      * @return void
      */
    /* Send mail of remaining amount from Admin to  Receiver method*/
    public function customMailSendMethodForLeftAmt($emailTemplateVariables, $senderInfo, $receiverInfo)
    {
        $this->temp_id = $this->getTemplateId(self::XML_PATH_GIFT_LEFT_AMT_EMAIL_TEMPLATE_FIELD);
        $this->inlineTranslation->suspend();
        $this->generateTemplate($emailTemplateVariables, $senderInfo, $receiverInfo);
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
