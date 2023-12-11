<?php

namespace Mageplaza\SocialLogin\Controller\Popup;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Helper\Address;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Registration;
use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Vaibhav\Otp\Model\OtpFactory;
ob_start();
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) 
 */
class checkoutsend extends \Magento\Framework\App\Action\Action {

    protected $jsonHelper;
    protected $_resource;

    /**
     * @var \Als\Agent\Model\AgentFactory
     */
    //protected $_agentFactory;

    /**
     * @param Context                    $context
     * @param JsonHelper                 $jsonHelper
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
    Context $context, 
    OtpFactory $otpFactory, 
    \Magento\Framework\Controller\Result\JsonFactory $jsonHelper, 
    \Magento\Framework\App\ResourceConnection $resource
    ) {

        parent::__construct($context);
        $this->jsonHelper = $jsonHelper;
        $this->_otpFactory = $otpFactory;
        $this->_resource = $resource;
    }

    public function execute() {
        // get mobile from form and generate token & store in db als_aent
        $isPost = $this->getRequest()->getPost();

        if ($isPost) {
            $model = $this->_otpFactory->create();
            // session data 
            $mobile = "";
            //load customer model with addfiter commandnd
            if (!empty($this->getRequest()->getPost('telephone'))) {
                $mobile = $this->getRequest()->getPost('telephone');
            }

            if (!empty($this->getRequest()->getPost('mobile')) and $mobile == "")
                $mobile = $this->getRequest()->getPost('mobile');

            /* if($mobile !="") {
              $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
              $bind = ['mobile' => $mobile];
              //$customerEntity = $connection->getTableName('sales_order');
              $select = $connection->select()->from(
              'customer_entity_varchar',
              ['value']
              )->where(
              'value = :mobile'
              );
              $bind['attribute_id'] = 188;// for server its 188 n for local its 190
              $select->where('attribute_id = :attribute_id');

              $Qryresult = $connection->fetchOne($select, $bind);
              if ($Qryresult) {
              $result['error']     = true;
              $result['message'] =  "A customer with the same Mobile already exists in an associated website.";
              }
              }else {
              $result['error']     = true;
              $result['message'] =  "Please enter Phone Number";
              } */

            if (!isset($result['error']) || !$result['error']) {
                $token = mt_rand(100000, 999999);

                $otpCollection = $this->_otpFactory->create()->getCollection()->addFilter('mobile', $mobile);
                //echo "Query -".$agentCollection->getSelect();
                $found = $otpCollection->getSize();

                if ($found > 0) {
                    //update entry 
                    $agentData = $otpCollection->getData();
                    $updateId = $agentData[0]['id'];

                    // verified den delete entry n update customer db 

                    $model->load($updateId);
                    $model->setData('otp', $token);
                } else {
                    // add new entry 
                    $model = $this->_otpFactory->create();
                    $DbId = $this->getRequest()->getParam('id');
                    $model->load($DbId);
                    $formData = array(
                        'mobile' => $mobile,
                        'otp' => $token
                    );

                    $model->addData($formData);
                }

                try {
                    // Save data to DB
                    $model->save();
                    // Send OTP 
        /*            $otp_type=$this->getRequest()->getPost('otp_type');
				if($otp_type=="call"){
					$smsurl = "http://voiceapi.smscountry.com/api";
					$smsMessage = "Your OTP number is ".$token;
					$data_json="api_key=5jT8wka2It6UgJIPJjmD&access_key=KeJh5wbDvWkYgUioDf7dgTupTB509nAyfgQXeUNd&xml=<request action='http://voiceapi.smscountry.com/api'><to>".$mobile."</to><speak>".$smsMessage."</speak><language>English</language><engine>2</engine><voice>Veena</voice></request>";
					//$data_json="api_key=5jT8wka2It6UgJIPJjmD&access_key=KeJh5wbDvWkYgUioDf7dgTupTB509nAyfgQXeUNd&xml=<request action='http://voiceapi.smscountry.com/api'><to>".$mobile."</to><speak>".$token."</speak><language>English</language><engine>1</engine><voice>Susan</voice></request>";
					$ch = curl_init(); 
					 if (!$ch){
						$result['error']     = true;
						$result['message'] =  "Couldn't initialize a cURL handle";
					 }
					 $ret = curl_setopt($ch, CURLOPT_URL,$smsurl);
					 curl_setopt ($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
					 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					 curl_setopt ($ch, CURLOPT_POSTFIELDS,$data_json);
					 $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					 
					// commented for testin uncommet it afetr deplyin on server 
					 $curlresponse = curl_exec($ch); // execute
					 
					 if(curl_errno($ch))
						$result['error']     = true;
						$result['message'] = curl_error($ch);
					
					 if (empty($ret)) {
						// some kind of an error happened
						$result['error']     = true;
						$result['message'] =  curl_error($ch);
						curl_close($ch); // close cURL handler
						
					 } else {
						$info = curl_getinfo($ch);
						$result['success']     = true;
						$result['message'] =  json_encode($info);
						//echo $info;
						curl_close($ch); // close cURL handler
					 }
				}*/
        //else{
					$password = "vaibhav";//40850740";
					$username = "vaibhav";//"alsvetcare";
					//$smsMessage = "Mr. Senthil has requested for $token and for $token area to contact this customer please call on $token or send an email on $token.";
					$smsMessage = "Your Vaibhav verification OTP code is $token. Code valid for 10 minutes only, one time use. Please DO NOT share this OTP with anyone.";
					/*$messagetype = "N";
					$DReports = "Y";*/
          $from = urlencode('STRIKR'); // assigned Sender_ID
          $sender = urlencode('VAIBAV');
					$smsurl = "https://www.smsstriker.com/API/sms.php?";
					//$mobilenumber = "91".$mobile;
					$numbers = $mobile;
          $message = urlencode($smsMessage);
					$data =
"username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1";
					//?User=$USERNAME&passwd=$PASSWORD&mobilenumber=$mobile&message=$smsMessage&mtype=N&DR=Y";
					$templateId="1407162513779268909";
					 $ch = curl_init(); 
					 if (!$ch){
						$result['error']     = true;
						$result['message'] =  "Couldn't initialize a cURL handle";
					 }
					 
					 $ret = curl_setopt($ch, CURLOPT_URL,$smsurl);
					 curl_setopt ($ch, CURLOPT_POST, 1);
					 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);          
					 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					 curl_setopt ($ch, CURLOPT_POSTFIELDS,"username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1"."&template_id="."$templateId");
					 $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					 
					// commented for testin uncommet it afetr deplyin on server 
					 $curlresponse = curl_exec($ch); // execute
				
					//print_r($curlresponse);
					//exit;
					if(curl_errno($ch))
						$result['error']     = true;
						$result['message'] = curl_error($ch);
					
					 if (empty($ret)) {
						// some kind of an error happened
						$result['error']     = true;
						$result['message'] =  curl_error($ch);
						curl_close($ch); // close cURL handler
						
					 } else {
						$info = curl_getinfo($ch);
						$result['success']     = true;
						$result['message'] =  json_encode($info);
						//echo $info;
						curl_close($ch); // close cURL handler
					 }
				//}
				

                    $result['success'] = true;
                    $result['message'] = "OTP sent";
                } catch (\Exception $e) {
                    //print_r( $e->getMessage());
                    $result['error'] = true;
                    $result['message'] = $e->getMessage();
                }
            } // if error not set 
            echo json_encode($result);
        }
    }
}