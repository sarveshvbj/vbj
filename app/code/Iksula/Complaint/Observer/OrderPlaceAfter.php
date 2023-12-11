<?php
namespace Iksula\Complaint\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
class OrderPlaceAfter implements ObserverInterface
{ 

    protected $orderRepository;
    protected $_helper;
    protected $_coreSession;

    public function __construct(  
    OrderRepositoryInterface $OrderRepositoryInterface,
    \Iksula\Complaint\Helper\Data $_helper,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
    \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        $this->orderRepository = $OrderRepositoryInterface;
         $this->_storeManager = $storeManager;
         $this->_transportBuilder = $transportBuilder;
         $this->_coreSession = $coreSession;
        $this->_helper=$_helper;

    }

    public function execute(\Magento\Framework\Event\Observer $observer) 
    {
           //Custom log:
          /* $writer = new \Zend\Log\Writer\Stream(BP.'/var/log/stackexchange.log');
           $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);*/
          //echo ' Cookie - '. $_COOKIE['cookie_name'];
          
           $order_ids = $observer->getEvent()->getOrderIds()[0];
           $order = $this->orderRepository->get($order_ids);
           $payment = $order->getPayment();
           $method = $payment->getMethodInstance();
           $payment_method = $method->getTitle();
           $entity_id = $order->getEntityId();
           $store = '';
            if($payment_method=='Pay at Store' && isset( $_COOKIE['cookie_name'])) 
            { 
                $store = $_COOKIE['cookie_name'];
                 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('sales_order_grid');
                 $sql= "Update $tableName Set store = '" . $store . "' where entity_id ='" . $entity_id . "'";
                 $connection->query($sql);
                 //unset($_COOKIE['cookie_name']);
            }
             if(isset( $_COOKIE['cookie_donation'])){
                 $donation = $_COOKIE['cookie_donation'];
                 $tableNames = $resource->getTableName('sales_order_grid');
                 $sql= "Update $tableName Set donation = '" . $donation . "' where entity_id ='" . $entity_id . "'";
                 $connection->query($sql);
                 unset($_COOKIE['cookie_donation']);
            }
           /*else 
            { 
                 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                 $connection = $resource->getConnection();
                 $tableName = $resource->getTableName('sales_order_grid');
                 $sql= "Update $tableName Set store = '" . $store . "' where entity_id ='" . $entity_id . "'";
                 $connection->query($sql); 
            }*/
           $item_name = "";
          foreach ($order->getAllItems() as $item) {
                if($item_name=="") {
                    $item_name = $item->getData()["name"];
                } else {
                    $item_name = $item_name . ", " . $item->getData()["name"];
                }
            }  
          //print_r($item_name);         
           $order_number = $order->getIncrementId();
           $this->_coreSession->start();
           $this->_coreSession->setMessage($order_number);
           $date = $order->getCreatedAt();
           $order_date = date("d-m-Y", strtotime($date));
           $customer_email = $order->getCustomerEmail();
           $customer_id = $order->getCustomerId();
           $customer_name = $order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname();
           $order_value = round($order->getGrandTotal(),2);
           $payment = $order->getPayment();
           $method = $payment->getMethodInstance();
           $payment_method = $method->getTitle();
           $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
           $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customer_id);
          /* foreach ($customerObj->getAddresses() as $address)
		   {
		    $country =  $address->getCountryId();
		    if($country == 'IN'){
		        $country = 'India';
		    }
            $region =  $address->getRegion();
		    }*/
           $postal = $order->getShippingAddress()->getPostcode();
           $city   = $order->getShippingAddress()->getCity();
           $street = $order->getShippingAddress()->getData('street');
           $mobile  = $order->getShippingAddress()->getTelephone();
           $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
           $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
           $baseUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
           $logo_url = $baseUrl.'/pub/media/email/logo/stores/1/logo_email.png';
           $fb_icon = $baseUrl.'/pub/media/social/rnb_ico_fb.png';
           $tw_icon = $baseUrl.'/pub/media/social/rnb_ico_tw.png';
           $yt_icon = $baseUrl.'/pub/media/social/rnb_ico_yt.png';
           $in_icon = $baseUrl.'/pub/media/social/rnb_ico_in.png';
           $ig_icon = $baseUrl.'/pub/media/social/rnb_ico_ig.png';
           $gp_icon = $baseUrl.'/pub/media/social/rnb_ico_gp.png';
           if($customer_email && $customer_email !=NULL){
            $emailTemplateVariable = array();
                $emailTempVariables['customer_name'] = $customer_name;
                $emailTempVariables['order_number'] = $order_number;
                $emailTempVariables['order_date'] = $order_date;
                $emailTempVariables['order_value'] = $order_value;
                $emailTempVariables['payment_method'] = $payment_method;
                /*$emailTempVariables['country'] = $country;
                $emailTempVariables['region'] = $region;*/
                $emailTempVariables['postal'] = $postal;
                $emailTempVariables['city'] = $city;
                $emailTempVariables['street'] = $street;
                $emailTempVariables['mobile'] = $mobile;
                $emailTempVariables['logo_url'] = $logo_url;
                $emailTempVariables['fb_icon'] = $fb_icon;
                $emailTempVariables['tw_icon'] = $tw_icon;
                $emailTempVariables['yt_icon'] = $yt_icon;
                $emailTempVariables['in_icon'] = $in_icon;
                $emailTempVariables['ig_icon'] = $ig_icon;
                $emailTempVariables['gp_icon'] = $gp_icon;
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('order_confirm_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo($customer_email)
                ->getTransport();
            $transports->sendMessage();
          }
           if($customer_id && $customer_id !=NULL){
            $firstname      = $order->getBillingAddress()->getFirstname();
            $lastname       = $order->getBillingAddress()->getLastname();
            $customer_name  = $firstname.' '.$lastname;
            $telephone_tmp  = $order->getBillingAddress()->getTelephone();
            $customer_email = $order->getBillingAddress()->getEmail();
            $postal         = $order->getBillingAddress()->getPostcode();
            $city           = $order->getBillingAddress()->getCity();
            $address_tmp    = $order->getBillingAddress()->getData('street');
            $message = "Your order for {{*product name*}} has been successfully placed. You will receive the tracking details, once the products get shipped. Thank you for shopping at VaibhavJewellers";
            $templateId="1407160957242995250";
            $template = str_replace("{{*product name*}}", $item_name, $message);
            $this->_helper->send_sms($telephone_tmp,$template,$templateId);
            }

         // $logger->info("latest order Id ===>".$order_id.'customer Email ==>'.$customer_email);
          //die();

    }


}