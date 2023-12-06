<?php

namespace Magegadgets\Videoform\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend\Log\Filter\Timestamp;
ob_start();

class Postnew extends \Magento\Framework\App\Action\Action
{
	const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
     
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
    protected $_helper;
    //protected $url;
     
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Iksula\Complaint\Helper\Data $_helper,
        //\Magento\Framework\UrlInterface $url,
        array $data = []
         
        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
        $this->_helper=$_helper;
        //$this->url = $url;
         
         
        parent::__construct($context);
         
          
    }
	public function execute()
    {
		$data = $this->getRequest()->getPostValue();
        $post = $this->getRequest()->getPostValue();
        $hrs = $data['hrs'];
        $min = $data['min'];
        $meri = $data['meri'];
        $time = $hrs.':'.$min.':'.$meri;
        $datavalue['type'] = $data['type'];
        $datavalue['name'] = $data['fullname'];
        $datavalue['mobile'] = $data['mobile'];
        $datavalue['email'] = $data['emails'];
        $datavalue['language'] = $data['language'];
        $datavalue['takedate'] = $data['takedate'];
        $datavalue['time'] = $time;
        $email = $data['emails'];
        $name = $data['fullname'];
        $mobile = $data['mobile'];
        $language = $data['language'];
        $date = $data['takedate'];
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		if ($data) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$model = $objectManager->create('Magegadgets\Videoform\Model\Videoform');
			$model->setData($datavalue);
			/*entry in Lead */
			$data_string = '[{"Attribute":"EmailAddress","Value":"'.$email.'"},{"Attribute":"FirstName","Value":"'.$name.'"},{"Attribute":"Source","Value":"Videoform"},{"Attribute":"Mobile","Value":"'.$mobile.'"},{"Attribute":"Notes","Value":"'.$date.' '.','.$time.' '.','.$language.'"}]';
            try
            {
            $curl = curl_init('https://api-in21.leadsquared.com/v2/LeadManagement.svc/Lead.Create?accessKey=u$r8558704a813544073cf33583654e3111&secretKey=42dda614196ea45f10ee0d324bca5097d84ca6dc');
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Content-Type:application/json",
                    "Content-Length:".strlen($data_string)
                    ));
            $json_response = curl_exec($curl);
            echo $json_response;
                curl_close($curl);
            } catch (Exception $ex) {
                curl_close($curl);
            }
			/*entry in Lead */
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
            $emailTempVariables['email'] = $data['emails'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['language'] = $data['language'];
            $emailTempVariables['time'] = $data['takedate'];
             /* for sms template*/
           $name = $data['fullname'];
		   $type = 'Video Shopping';
		   $language = $data['language'];
		   $email = $data['emails'];
		   $mobile = $data['mobile'];
		   $time = $data['takedate'];
		   $message = "Mr. {{*name*}} has requested for {{*type*}} and for {{*language*}} language at {{*time*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
		   $template = str_replace("{{*name*}}", $name, $message);
		   $template = str_replace("{{*language*}}", $language, $template); 
		   $template = str_replace("{{*type*}}", $type, $template);
		   $template = str_replace("{{*mobile*}}", $mobile, $template);
		   $template = str_replace("{{*email*}}", $email, $template);
		   $template = str_replace("{{*time*}}", $time, $template); 
		   $telephone = '9177403012';
		   $this->_helper->send_sms($telephone,$template);
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
            
            /**/
				$this->messageManager->addSuccess(__('The Purpose details has been submitted successfully.'));
                /*$resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;*/
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                 
        } catch(\Exception $e){
			echo $e->getMessage();
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
			$resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
           // exit;
        }
			
			//======================================== Email Ends =======================================//

			
		}else{
				
			 		$resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect; 
		}
	}
	
}