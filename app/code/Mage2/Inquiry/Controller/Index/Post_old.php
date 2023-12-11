<?php

namespace Mage2\Inquiry\Controller\Index;

use Exception;
use Mage2\Inquiry\Api\InquiryRepositoryInterface;
use Mage2\Inquiry\Helper\Data as HelperData;
use Iksula\Complaint\Helper\Data as MessageHelper;
use Mage2\Inquiry\Model\InquiryFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Post extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var InquiryFactory
     */
    protected $inquiryFactory;

    /**
     * @var HelperData $helperData
     */
    protected $helperData;

    /**
     * @var Logger
     */
    protected $logger;
    
     /**
     * @var helper
     */
    protected $_helper;
    /**
     * Post constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param InquiryFactory $inquiryFactory
     * @param InquiryRepositoryInterface $inquiryRepository
     * @param HelperData $helperData
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        InquiryFactory $inquiryFactory,
        LoggerInterface $logger,
        InquiryRepositoryInterface $inquiryRepository,
        MessageHelper $_helper,
        HelperData $helperData
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inquiryFactory = $inquiryFactory;
        $this->inquiryRepository = $inquiryRepository;
        $this->helperData = $helperData;
        $this->_helper=$_helper;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        $product = $this->helperData->getProductBySku($post['sku']);
        $inquiry = $this->inquiryFactory->create();

        if (!$post && !$product->getId()) {
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }

        try {
            $post ['status'] = 1;
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $inquiry->setData($post);
            $this->inquiryRepository->save($inquiry);

           $name = $post['name'];
           $type = 'Product Enquiry';
           $email = $post['email'];
           $mobile = $post['mobile_number'];
           $remarks= $post['message'];
           $product_url = $post['product_url'];
          
           $message = "Mr. {{*name*}} has requested for {{*type*}} and for product url {{*product_url*}} area to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
           $template = str_replace("{{*name*}}", $name, $message);
           $template = str_replace("{{*type*}}", $type, $template); 
           $template = str_replace("{{*product_url*}}", $product_url, $template); 
           $template = str_replace("{{*mobile*}}", $mobile, $template);
           $template = str_replace("{{*email*}}", $email, $template);
           
           $telephone = '9177403012';
           $this->_helper->send_sms($telephone,$template);


              $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/product_enq.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);

           //Sending Data to API
$curTime=date('Y-m-d H:i:s', time());
$datetime = new \DateTime($curTime);
$currenDateTime = $datetime->format(\DateTime::ATOM);
if(empty($remarks)) {
    $remarks="NaN";
}

            $fields = json_encode(array("data" => array(["Last_Name" => $name,"Product_URL" => $product_url,"Lead_Status" => "Opportunity","Lead_Source" => $type,"Remarks" => $remarks,"Date_Time" => $currenDateTime,"Email" => $email,"Phone" => $mobile])));
        
      //Latest Code
       $latesttoken_coll = $objectManager->create('Magegadgets\Videoform\Model\ResourceModel\Zohoapi\Collection')->addFieldToFilter('id', array('eq' => '1'));

          if(count($latesttoken_coll->getData())) {
      $logger->info('Token inside loop');
            $latest_token='';
            foreach($latesttoken_coll as $row){
            $latest_token = $row->getAccessToken();
            }
           $status= $this->sendZohoApi($fields,$latest_token);
           $logger->info('Sending Data to API start_  '.$status);
           if($status != 'success') {
              $refreshtoken = $this->refZohoToken();
              $logger->info('Token Referesh token '.$refreshtoken);
               $model_new = $objectManager->create('Magegadgets\Videoform\Model\ZohoapiFactory')->create()->load(1);
            $model_new->setAccessToken($refreshtoken);
            $model_new->save(); 
            $logger->info('Token Referesh token saved ');
            $status = $this->sendZohoApi($fields,$refreshtoken);
            $logger->info('Again sending data to API Status_ '.$status);
            echo $status;
           } else {
            echo "success";
             $logger->info('Sending Data to API start_ Sent');
           }
          } else {
            $data_values['access_token'] = '1000.9903a8d8b493e5594380583a01d645fa.3f09d8237a854fa2b7bf9eb04c59b833';
            $data_values['refresh_token'] = '1000.e8856a9d23e08fc994b7f6a34353f57e.ecf33d7586812b0a21941a08dd660dc3';
            $data_values['created_at'] = date('Y-m-d H:i:s', time());
            $data_values['updated_at'] = date('Y-m-d H:i:s', time());
            $model = $objectManager->create('Magegadgets\Videoform\Model\Zohoapi');
            $model->setData($data_values);
            $model->save();

          }


            try {
                $this->helperData->sendCustomerEmail($post);
            } catch (Exception $e) {
                $this->logger->error($e->getMessage());
            }

            if ($this->helperData->isEmailSendToAdmin()) {
                try {
                    $this->helperData->sendAdminEmail($post);
                } catch (Exception $e) {
                    $this->logger->error($e->getMessage());
                }
            }
            $this->messageManager->addSuccess(__('Thank you for question , Our team will contact you soon. '));
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        } catch (Exception $e) {
            $this->messageManager->addError(__('We can\'t process your inquiry right now. Sorry, please contact support team.'));
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
    }
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
            if(isset($arr['status'])) {
            $status = $arr['status'];
            } else {
                $status = $arr['data'][0]['code'];
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

    public function getUpdateFields($fields,$lead_id) {
        $arr_convert = json_decode($fields,true);
        $new_fields = json_encode(array("data" => array(["id" => $lead_id,"Email" => $arr_convert['data'][0]['Email'],"Lead_Status" => $arr_convert['data'][0]['Lead_Status'],"Lead_Source" => $arr_convert['data'][0]['Lead_Source'],"Date_Time" => $arr_convert['data'][0]['Date_Time'],"Product_URL" => $arr_convert['data'][0]['Product_URL'],"Remarks" => $arr_convert['data'][0]['Remarks'],"Last_Name" => $arr_convert['data'][0]['Last_Name'],"Phone" => $arr_convert['data'][0]['Phone']]))); 
       return $new_fields;
    }

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
