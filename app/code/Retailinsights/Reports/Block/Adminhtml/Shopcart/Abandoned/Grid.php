<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Retailinsights\Reports\Block\Adminhtml\Shopcart\Abandoned;


/**
 * Adminhtml abandoned shopping carts report grid block
 *
 * @method \Magento\Reports\Model\ResourceModel\Quote\Collection getCollection()
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Grid extends \Magento\Reports\Block\Adminhtml\Grid\Shopcart
{
    /**
     * @var \Magento\Reports\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $_quotesFactory;
    protected $quoteCartFactory;
    public $collectionAban;

    private $quoteItemFactory;
private $itemResourceModel;
private $customerRepository;
   private $addressRepository;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Reports\Model\ResourceModel\Quote\CollectionFactory $quotesFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Reports\Model\ResourceModel\Quote\CollectionFactory $quotesFactory,
        \Magento\Quote\Model\QuoteFactory $quoteCartFactory,

        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
  		\Magento\Quote\Model\ResourceModel\Quote\Item $itemResourceModel,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        array $data = []
    ) {
        $this->_quotesFactory = $quotesFactory;
         $this->quoteCartFactory = $quoteCartFactory;

        $this->quoteItemFactory = $quoteItemFactory;
   		$this->itemResourceModel = $itemResourceModel;
        $this->customerRepository = $customerRepository;
         $this->addressRepository = $addressRepository;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('gridAbandoned');
    }

    public function abandonedCollection(){
        // var_dump($this->$collection);
    	// $data = $this->_prepareCollection();
        if(parent::_prepareCollection()){
            $abdCollection = parent::_prepareCollection();
            return $abdCollection;
        }else{
            return;
        }
       
    }
    public function mine(){
    	return "this function is called";
    }
    /**
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        
  

        /** @var $collection \Magento\Reports\Model\ResourceModel\Quote\Collection */
        $collection = $this->_quotesFactory->create();


        $filter = $this->getParam($this->getVarNameFilter(), []);
        if ($filter) {
            $filter = base64_decode($filter);
            parse_str(urldecode($filter), $data);
        }

        if (!empty($data)) {
            $collection->prepareForAbandonedReport($this->_storeIds, $data);
        } else {
            $collection->prepareForAbandonedReport($this->_storeIds);
        }
        
        $resultCollection=array();
        foreach ($collection as $ky => $value) {
            $telephone="";
            try {
            $customer = $this->customerRepository->getById($value->getCustomerId());
            $billingAddressId = $customer->getDefaultBilling();
            $shippingAddressId = $customer->getDefaultShipping();
            $billingAddress = $this->addressRepository->getById($billingAddressId);
            $telephone = $billingAddress->getTelephone();
            $first_name = $customer->getFirstname();
            $cust_email = $customer->getEmail();
           
    } catch (\Magento\Framework\Exception\NoSuchEntityException $noSuchEntityException) {
        $telephone="";
    }

           
    		$carts = $this->quoteItemFactory->create();
        	$cartCollection = $carts->getCollection()->addFieldToFilter('quote_id',$value->getEntityId());

            $name = "";
            $sku = "";
            foreach ($cartCollection->getData() as $quoteItem) {
            
          

                $quote_id = $quoteItem['quote_id'];

            	$name .= $quoteItem['name'];
            	$name .= ', ';

            	$sku .= $quoteItem['sku'];
            	$sku .= ', ';
           
            }
            $value->setData('name',$name);
            $value->setData('telephone',$telephone);
            $value->setData('sku',$sku);

//      if(!empty($telephone)) {

//         $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/abondonedcart.log');
// $logger = new \Zend\Log\Logger();
// $logger->addWriter($writer);


//             $curTime=date('Y-m-d H:i:s', time());
// $datetime = new \DateTime($curTime);
// $currenDateTime = $datetime->format(\DateTime::ATOM);
//              $fields = json_encode(array("data" => array(["Last_Name" => $first_name,"Product_URL" => $sku,"Lead_Status" => "Opportunity","Lead_Source" => "Abandoned Cart","Date_Time" => $currenDateTime,"Email" => $cust_email,"Phone" => $telephone])));
        
//       //Latest Code
//         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//        $latesttoken_coll = $objectManager->create('Magegadgets\Videoform\Model\ResourceModel\Zohoapi\Collection')->addFieldToFilter('id', array('eq' => '4'));

//           if(count($latesttoken_coll->getData())) {
//       $logger->info('Token inside loop');
//             $latest_token='';
//             foreach($latesttoken_coll as $row){
//             $latest_token = $row->getAccessToken();
//             }
//            $status= $this->sendZohoApi($fields,$latest_token);
//            $logger->info('Sending Data to API start_  '.$status);
//            if($status != 'success') {
//               $refreshtoken = $this->refZohoToken();
//               $logger->info('Token Referesh token '.$refreshtoken);
//                $model_new = $objectManager->create('Magegadgets\Videoform\Model\ZohoapiFactory')->create()->load(4);
//             $model_new->setAccessToken($refreshtoken);
//             $model_new->save(); 
//             $logger->info('Token Referesh token saved ');
//             $status = $this->sendZohoApi($fields,$refreshtoken);
//             $logger->info('Again sending data to API Status_ '.$status);
//             echo $status;
//            } else {
//             echo "success";
//              $logger->info('Sending Data to API start_ Sent');
//            }
//           } else {
//             $data_values['access_token'] = '1000.9903a8d8b493e5594380583a01d645fa.3f09d8237a854fa2b7bf9eb04c59b833';
//             $data_values['refresh_token'] = '1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3';
//             $data_values['created_at'] = date('Y-m-d H:i:s', time());
//             $data_values['updated_at'] = date('Y-m-d H:i:s', time());
//             $model = $objectManager->create('Magegadgets\Videoform\Model\Zohoapi');
//             $model->setData($data_values);
//             $model->save();

//           }

//       }



        }

      
        $this->setCollection($collection);
        parent::_prepareCollection();
        $this->getCollection()->resolveCustomerNames();

         

        return $this;
    }
    /**
     * @param array $column
     *
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        $field = $column->getFilterIndex() ? $column->getFilterIndex() : $column->getIndex();
        $skip = ['subtotal', 'customer_name', 'email'];

        if (in_array($field, $skip)) {
            return $this;
        }

        parent::_addColumnFilterToCollection($column);


        return $this;
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'customer_name',
            [
                'header' => __('Customer'),
                'index' => 'customer_name',
                'sortable' => false,
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'email',
            [
                'header' => __('Email'),
                'index' => 'email',
                'sortable' => false,
                'header_css_class' => 'col-email',
                'column_css_class' => 'col-email'
            ]
        );
         $this->addColumn(
            'telephone',
            [
                'header' => __('Mobile'),
                'index' => 'telephone',
                'sortable' => false,
                'header_css_class' => 'col-number',
                'column_css_class' => 'col-number'
            ]
        );

        $this->addColumn(
            'items_count',
            [
                'header' => __('Products'),
                'index' => 'items_count',
                'sortable' => false,
                'type' => 'number',
                'header_css_class' => 'col-number',
                'column_css_class' => 'col-number'
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'sortable' => false,
                
                'header_css_class' => 'col-number',
                'column_css_class' => 'col-number'
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'sortable' => false,
                
                'header_css_class' => 'col-number',
                'column_css_class' => 'col-number'
            ]
        );

        $this->addColumn(
            'items_qty',
            [
                'header' => __('Quantity'),
                'index' => 'items_qty',
                'sortable' => false,
                'type' => 'number',
                'header_css_class' => 'col-qty',
                'column_css_class' => 'col-qty'
            ]
        );

        if ($this->getRequest()->getParam('website')) {
            $storeIds = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
        } elseif ($this->getRequest()->getParam('group')) {
            $storeIds = $this->_storeManager->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
        } elseif ($this->getRequest()->getParam('store')) {
            $storeIds = [(int)$this->getRequest()->getParam('store')];
        } else {
            $storeIds = [];
        }
        $this->setStoreIds($storeIds);
        $currencyCode = $this->getCurrentCurrencyCode();

        $this->addColumn(
            'subtotal',
            [
                'header' => __('Subtotal'),
                'type' => 'currency',
                'currency_code' => $currencyCode,
                'index' => 'subtotal',
                'sortable' => false,
                'renderer' => 'Magento\Reports\Block\Adminhtml\Grid\Column\Renderer\Currency',
                'rate' => $this->getRate($currencyCode),
                'header_css_class' => 'col-subtotal',
                'column_css_class' => 'col-subtotal'
            ]
        );

        $this->addColumn(
            'coupon_code',
            [
                'header' => __('Applied Coupon'),
                'index' => 'coupon_code',
                'sortable' => false,
                'header_css_class' => 'col-coupon',
                'column_css_class' => 'col-coupon'
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created'),
                'type' => 'datetime',
                'index' => 'created_at',
                'filter_index' => 'main_table.created_at',
                'sortable' => false,
                'header_css_class' => 'col-created',
                'column_css_class' => 'col-created'
            ]
        );

        $this->addColumn(
            'updated_at',
            [
                'header' => __('Updated'),
                'type' => 'datetime',
                'index' => 'updated_at',
                'filter_index' => 'main_table.updated_at',
                'sortable' => false,
                'header_css_class' => 'col-updated',
                'column_css_class' => 'col-updated'
            ]
        );

        $this->addColumn(
            'remote_ip',
            [
                'header' => __('IP Address'),
                'index' => 'remote_ip',
                'sortable' => false,
                'header_css_class' => 'col-ip',
                'column_css_class' => 'col-ip'
            ]
        );

        $this->addExportType('*/*/exportAbandonedCsv', __('CSV'));
        $this->addExportType('*/*/exportAbandonedExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('customer/index/edit', ['id' => $row->getCustomerId(), 'active_tab' => 'cart']);
    }


    /**
     * @param string $fields
     * @param string $reftoken
     * @return string
     */
         public function sendZohoApi($fields,$reftoken) {
   
    $curl = curl_init('https://www.zohoapis.com/crm/v2/Leads');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken,
                    "Content-Length:".strlen($fields)
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $arr = json_decode($jsonobj,true);
             $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/abondonedcart.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
            $logger->info(print_r($arr));
            if(isset($arr['status'])) {
            $status = $arr['status'];
            } else {
                $status = $arr['data'][0]['code'];
                echo $status;
                 $lead_id=$arr['data'][0]['details']['id'];
                if($status=="DUPLICATE_DATA") {
                    $update_status="";
                     $updatedfields = $this->getUpdateFields($fields,$lead_id);
                     $status = $this->updateZohoApi($updatedfields,$reftoken);
                } else {
                    $status='success';
                } 
            }
           
            curl_close($curl);
            return $status;
    }

   /**
     * @param string $fields
     * @param string $lead_id
     * @return string
     */
    public function getUpdateFields($fields,$lead_id) {
        $arr_convert = json_decode($fields,true);
        $new_fields = json_encode(array("data" => array(["id" => $lead_id,"Email" => $arr_convert['data'][0]['Email'],"Lead_Status" => $arr_convert['data'][0]['Lead_Status'],"Lead_Source" => $arr_convert['data'][0]['Lead_Source'],"Date_Time" => $arr_convert['data'][0]['Date_Time'],"Product_URL" => $arr_convert['data'][0]['Product_URL'],"Last_Name" => $arr_convert['data'][0]['Last_Name'],"Phone" => $arr_convert['data'][0]['Phone']]))); 
       return $new_fields;
    }

   /**
     * @param string $updatedfields
     * @param string $reftoken
     * @return string
     */
    public function updateZohoApi($updatedfields,$reftoken) {

   $curl = curl_init('https://www.zohoapis.com/crm/v2/Leads');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $updatedfields);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Authorization: Zoho-oauthtoken ".$reftoken,
                    "Content-Length:".strlen($updatedfields)
                    ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl);
            $arr = json_decode($jsonobj,true);
            $status = $arr['data'][0]['status'];
            curl_close($curl); 
            return $status;
    }
   /**
     *@return string
     */
    public function refZohoToken() {

    $curl_access= curl_init('https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3&client_id=1000.JNJ5X1SHVCC3EAHX8J4DNAXPQ5SYYH&client_secret=cbe1963424157859fc22285e7eb01e17dd1fc4d039&grant_type=refresh_token');
            curl_setopt($curl_access, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl_access, CURLOPT_HEADER, 0);
            curl_setopt($curl_access, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_access, CURLOPT_HTTPHEADER, array("Content-Length:0"));
            curl_setopt($curl_access, CURLOPT_RETURNTRANSFER, true);
            $jsonobj = curl_exec($curl_access);
            $arr_ref = json_decode($jsonobj,true);
            $reftoken = $arr_ref['access_token'];
            curl_close($curl_access);
            return $reftoken;   
    }
}
