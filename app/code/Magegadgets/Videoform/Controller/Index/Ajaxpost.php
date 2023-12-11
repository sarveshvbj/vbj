<?php

namespace Magegadgets\Videoform\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend\Log\Filter\Timestamp;
ob_start();
date_default_timezone_set('Asia/Kolkata');
class Ajaxpost extends \Magento\Framework\App\Action\Action
{
    const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
     
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
    protected $_helper;
    protected $jsonFactory;
    //protected $url;
     
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Iksula\Complaint\Helper\Data $_helper,
        //\Magento\Framework\UrlInterface $url,
        array $data = []
         
        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->jsonFactory = $jsonFactory;
        $this->messageManager = $context->getMessageManager();
        $this->_helper=$_helper;
        //$this->url = $url;
         
         
        parent::__construct($context);
         
          
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $post = $this->getRequest()->getPostValue();

        $formtype= $data['type'];
         if($formtype=='product') {
            $form_remarks= $data['remarks'];
            $form_product= $data['product'];
        } else {
        $form_remarks='';
        $form_product ='';
        }

        $hrs = $data['hrs'];
        $min = $data['min'];
        $meri = $data['meri'];
        $time = $hrs.':'.$min.':'.$meri;
        $datavalue['type'] = $data['type'];
        $datavalue['name'] = $data['fullname'];
        $datavalue['mobile'] = $data['mobile'];
        $datavalue['email'] = $data['email'];
        $datavalue['language'] = $data['language'];
        $datavalue['takedate'] = date('Y-m-d', strtotime($data['takedate']));;
        $datavalue['remarks'] = $form_remarks;
        $datavalue['product'] = $form_product;
        $datavalue['time'] = $time;
        $email = $data['email'];
        $name = $data['fullname'];
        $mobile = $data['mobile'];
        $language = $data['language'];
        $date = $data['takedate'];
        $remarks = $form_remarks;
        $product = $form_product;
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($data) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $model = $objectManager->create('Magegadgets\Videoform\Model\Videoform');
            $model->setData($datavalue);
            try {
                $model->save();
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                    $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                } catch (\RuntimeException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('Something went wrong while saving the Purposedata.'));
                   $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                }
            //======================================== Email starts =======================================//
            
        try
        {
            $emailTempVariables['name'] = $data['fullname'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['language'] = $data['language'];
            $emailTempVariables['time'] = $data['takedate'];
             /* for sms template*/
           $name = $data['fullname'];
           $type = 'Video Shopping';
           $language = $data['language'];
           $email = $data['email'];
           $mobile = $data['mobile'];
           $time = $time = date('Y-m-d', strtotime($data['takedate']));;
            $message = "Mr. {{*name*}} has requested for {{*type*}} {{*product*}} and for {{*language*}} language at {{*time*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
           $template = str_replace("{{*name*}}", $name, $message);
           $template = str_replace("{{*language*}}", $language, $template); 
           $template = str_replace("{{*type*}}", $type, $template);
           $template = str_replace("{{*mobile*}}", $mobile, $template);
           $template = str_replace("{{*email*}}", $email, $template);
           $template = str_replace("{{*time*}}", $time, $template); 
           $template = str_replace("{{*product*}}", $product, $template);    
           $telephone = '9177403012';
           $templateId="";
           $this->_helper->send_sms($telephone,$template,$templateId);
                      //$refreshtoken=$this->refZohoToken();
           //Entry in Lead//
     //$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/stickyfooter.log');
//$logger = new \Zend\Log\Logger();
$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/stickyfooter.log');
$logger = new \Zend_Log();
$logger->addWriter($writer);

$curTime=date('Y-m-d H:i:s', time());
$datetime = new \DateTime($curTime);
$currenDateTime = $datetime->format(\DateTime::ATOM);
if(empty($language) || $language=="Odiya") {
$language="Other";
}
if(empty($remarks)) {
    $remarks = "NaN";
}

 $fields = json_encode(array("data" => array(["Last_Name" => $name,"Product_URL" => $product,"Lead_Status" => "New-VS","Lead_Source" => "Video form","Language" => $language,"Remarks" => $remarks,"Date_Time" => $currenDateTime,"Email" => $email,"Phone" => $mobile])));
        
      //Latest Code
       $latesttoken_coll = $objectManager->create('Magegadgets\Videoform\Model\ResourceModel\Zohoapi\Collection')->addFieldToFilter('id', array('eq' => '4'));

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
               $model_new = $objectManager->create('Magegadgets\Videoform\Model\ZohoapiFactory')->create()->load(4);
            $model_new->setAccessToken($refreshtoken);
            $model_new->save(); 
            $logger->info('Token Referesh token saved ');
            $status = $this->sendZohoApi($fields,$refreshtoken);
            $logger->info('Again sending data to API Status_ '.$status);
            //echo $status;
           } else {
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

          //End Entry in Lead

            /* for sms template*/
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            //$store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('videoform_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo('vaibhavanalytics@gmail.com')
                ->getTransport();
            $transports->sendMessage();
            
        //          $jsonData = ['result' => ['status' => 200, 'success' => 1, 'message' => 'Your Detail Added Successfully']];
        // $result = $this->jsonFactory->create()->setData($jsonData);
        // return $result;
                $this->messageManager->addSuccess(__('The Purpose details has been submitted successfully.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); 
                return $resultRedirect;


                /*$customRedirectionUrl = $this->url->getUrl('work-with-us');
                $this->responseFactory->create()->setRedirect($customRedirectionUrl)->sendResponse();*/
                 
        } catch(\Exception $e){
             $jsonData = ['result' => ['status' => 200, 'success' => 0, 'message' => $e->getMessage()]];
        $result = $this->jsonFactory->create()->setData($jsonData);
        return $result;
        }
            
            //======================================== Email Ends =======================================//

            
        }else{
                
             $jsonData = ['result' => ['status' => 200, 'success' => 0, 'message' => 'Your Request is empty']];
        $result = $this->jsonFactory->create()->setData($jsonData);
        return $result;
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
        $new_fields = json_encode(array("data" => array(["id" => $lead_id,"Email" => $arr_convert['data'][0]['Email'],"Lead_Status" => $arr_convert['data'][0]['Lead_Status'],"Lead_Source" => $arr_convert['data'][0]['Lead_Source'],"Date_Time" => $arr_convert['data'][0]['Date_Time'],"Product_URL" => $arr_convert['data'][0]['Product_URL'],"Last_Name" => $arr_convert['data'][0]['Last_Name'],"Language" => $arr_convert['data'][0]['Language'],"Remarks" => $arr_convert['data'][0]['Remarks'],"Phone" => $arr_convert['data'][0]['Phone']]))); 
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