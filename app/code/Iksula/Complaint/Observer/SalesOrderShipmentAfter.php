<?php

namespace Iksula\Complaint\Observer;

use Magento\Framework\Event\ObserverInterface;

class SalesOrderShipmentAfter implements ObserverInterface
{
	  /**
     * @var eventManager
     */
    protected $_eventManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    protected $_shipmentItemCollectionFactory;

     protected $_helper;
     protected $coreRegistry = null;

     public function __construct(
        \Magento\Framework\Event\Manager $eventManager,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Sales\Model\ResourceModel\Order\Shipment\Item\CollectionFactory $shipmentItemCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Iksula\Complaint\Helper\Data $_helper,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Registry $registry
    ) {
        $this->_eventManager = $eventManager;
        $this->_objectManager = $objectManager;  
        $this->_storeManager = $storeManager; 
        $this->_shipmentItemCollectionFactory = $shipmentItemCollectionFactory;
        $this->_helper=$_helper;
        $this->_transportBuilder = $transportBuilder;
        $this->coreRegistry = $registry;
           }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
       $shipment = $observer->getEvent()->getShipment();
       foreach ($shipment->getTracksCollection() as $item) {    
            $tids = $item->getTrackingNumber();
            $cps = $item->getTitle();   
        }
        $shipmentdata = $item->getData();
        $cp = $shipmentdata['title'];
        $tid = $shipmentdata['track_number'];
       $order = $shipment->getOrder();
       $orderId = $order->getIncrementId();
       $telephone = $order->getBillingAddress()->getTelephone();
       if($telephone && $telephone !=NULL){
       $event = $observer->getEvent();
       $shipments = $event->getShipment();
       $sid = $shipments->getId();
       /*$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('sales_shipment_track'); 
            $sql_title = "SELECT title FROM " . $tableName ." WHERE order_id=".$orderId;
            $title = $connection->fetchOne($sql_title);
           $cp = 1; 
           $tid = 1; 
           if(count($title) > 0){
              $cp = $title;
           }else{
               $cp = '';
           }
          $sql_track_number = "SELECT track_number FROM " . $tableName ." WHERE order_id=".$orderId;
            $track_number = $connection->fetchOne($sql_track_number); 
           if(count($track_number) > 0){
              $tid = $track_number;
           }else{
               $tid = '';
           }*/
       $message = "Your order with shipment id {{*sid*}} has been dispatched with {{*courier partner*}} with tracking ID {{*tid*}}";
       $templateId="1407160957270289012";
       $template = str_replace("{{*sid*}}", $sid, $message);
       if($cp){
       $template = str_replace("{{*courier partner*}}", $cp, $template);
       }
       if($tid){
       $template = str_replace("{{*tid*}}", $tid, $template);
       }
       //echo $template;
       $this->_helper->send_sms($telephone,$template,$templateId);
      }
       
      /* email template code from here */
  $customer_name = $order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname();
       $order_number = $order->getIncrementId();
      /*$this->_coreSession->start();
      $this->_coreSession->setMessage($order_number);*/
      $date = $order->getCreatedAt();
      $order_date = date("d-m-Y", strtotime($date));
      $customer_email = $order->getCustomerEmail();
      $customer_id = $order->getCustomerId();
      $order_value = round($order->getGrandTotal(),2);
      $payment = $order->getPayment();
      $method = $payment->getMethodInstance();
      $payment_method = $method->getTitle();
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customer_id);
           foreach ($customerObj->getAddresses() as $address)
       {
            $country =  $address->getCountryId();
            if($country == 'IN'){
                $country = 'India';
            }
            $region =  $address->getRegion();
        }
           $postal = $order->getShippingAddress()->getPostcode();
           $city   = $order->getShippingAddress()->getCity();
           $street = $order->getShippingAddress()->getData('street');
           $mobile = $order->getShippingAddress()->getTelephone();
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
          $emailTempVariables['country'] = $country;
          $emailTempVariables['region'] = $region;
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
          $emailTempVariables['cp'] = $cp;
          $emailTempVariables['tid'] = $tid;
          $postObjects='';
           $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('order_shipment')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' =>@$postObjects])
                ->setFrom('general')
                ->addTo($customer_email)
                ->getTransport();
            $transports->sendMessage();
          /*  if($transports->sendMessage()){
                echo "mail sent";
            }else{
                echo "mail did not sent";
            }
            die();
          }*/
    }
}
}