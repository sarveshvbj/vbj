<?php

namespace Magegadgets\Personalizejewellery\Controller\Index; 

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
     
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Iksula\Complaint\Helper\Data $_helper,
        array $data = []
         
        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
        $this->_helper=$_helper;
         
         
        parent::__construct($context);
         
         
    }
	public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		$post = $this->getRequest()->getPostValue();
		unset($data['submit']);
		unset($post['submit']);

		
		/*echo "<pre>";
		print_r($data);
		echo "<br/>";
		print_r($post);
		echo "</pre>";
		exit();*/
		//var_dump($data);die;
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		if ($data) {

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

			$model = $objectManager->create('Magegadgets\Personalizejewellery\Model\Personalizejewellery');
			
			try{
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'image']
				);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				/** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
				$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				/** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
					->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('personalizejewellery_personalizejewellery'));
					if($result['error']==0)
					{
						$data['image'] = 'personalizejewellery_personalizejewellery' . $result['file'];
					}
			} catch (\Exception $e) {
				//unset($data['image']);
            }
	/*echo "<pre>";
		print_r($data);
		echo "<br/>";
		print_r($post);
		echo "</pre>";
		exit();*/
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseurl = $storeManager->getStore()->getBaseUrl();
		if ($data['type']== 1){
		  $name = $data['fullname'];
		  $email = $data['email'];
		  $mobile = $data['mobile'];
		  $budget = $data['budget'];
		  $image = $data['image'];
		  $details = $data['details'];
		}
		if ($data['type']== 2){
		  $name = $data['fullname'];
		  $email = $data['email'];
		  $mobile = $data['mobile'];
		  $sku = $data['product_sku'];
		  $details = $data['details'];
		}
		if ($data['type']== 3){
		  $name = $data['fullname'];
		  $email = $data['email'];
		  $mobile = $data['mobile'];
		  $details = $data['details'];
		}
		
		$model->setData($data);
		 /*entry in Lead */
		 if($data['type']== 1){
            $data_string = '[{"Attribute":"EmailAddress","Value":"'.$email.'"},{"Attribute":"FirstName","Value":"'.$name.'"},{"Attribute":"Source","Value":"Upload Design"},{"Attribute":"Mobile","Value":"'.$mobile.'"},{"Attribute":"mx_Budget","Value":"'.$budget.'"},{"Attribute":"mx_Details_of_Jewellery","Value":"'.$details.'"},{"Attribute":"mx_Image","Value":"'.$image.'"}]';
         
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
		 }
			
			try {
				
				$model->save();

				//$this->messageManager->addSuccess(__('The Purpose details has been submitted successfully.'));

			/*		$resultRedirect = $this->resultRedirectFactory->create();
					$resultRedirect->setRefererOrBaseUrl();
					return $resultRedirect; */
				} catch (\Magento\Framework\Exception\LocalizedException $e) {
					$this->messageManager->addError($e->getMessage());
				} catch (\RuntimeException $e) {
					$this->messageManager->addError($e->getMessage());
				} catch (\Exception $e) {
					$this->messageManager->addException($e, __('Something went wrong while saving the Purposedata.'));
				}
			
			//======================================== Email starts =======================================//
			
			try
        {
            // Send Mail
            /*$this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
             
            $sender = [
                'name' => $post['fullname'],
                'email' => $post['email']
            ];
             
            $sentToEmail = $this->_scopeConfig ->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
             
            $sentToName = $this->_scopeConfig ->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			*/
            /*
			echo $sentToEmail;
			echo $sentToName;
			exit(); */
			
			/*if($post['type']== 1){
            $transport = $this->_transportBuilder->setTemplateIdentifier('customemail_email_template');
			}
			
			if($post['type']== 2){
            $transport = $this->_transportBuilder->setTemplateIdentifier('customemail_email_template2');
			}
			
			if($post['type']== 3){
            $transport = $this->_transportBuilder->setTemplateIdentifier('customemail_email_template3');
			}
			
            $transport = $this->_transportBuilder->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                );*/
            if($post['type']== 1){
			$emailTempVariables['name'] = $data['fullname'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['budget'] = $data['budget'];
            $emailTempVariables['details'] = $data['details'];
            /* for sms template*/
           $name = $post['fullname'];
		   $type = 'Upload Design';
		   $budget = $post['budget'];
		   $email = $post['email'];
		   $mobile = $post['mobile'];
		   $message = "Mr. {{*name*}} has a budget of {{*budget*}} and has requested for {{*type*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
		   $template = str_replace("{{*name*}}", $name, $message);
		   $template = str_replace("{{*budget*}}", $budget, $template); 
		   $template = str_replace("{{*type*}}", $type, $template);
		   $template = str_replace("{{*mobile*}}", $mobile, $template);
		   $template = str_replace("{{*email*}}", $email, $template);   
		   $telephone = '9177403012';
		   $this->_helper->send_sms($telephone,$template);
			/* for sms template*/
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('uploaddesign_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo('vaibhavanalytics@gmail.com')
                ->getTransport();
            $transports->sendMessage();
            $this->messageManager->addSuccess('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
			$resultRedirect->setRefererOrBaseUrl();
			return $resultRedirect;
				}
			if($post['type']== 2){
			$emailTempVariables['name'] = $data['fullname'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['productsku'] = $data['product_sku'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['budget'] = $data['budget'];
            $emailTempVariables['details'] = $data['details'];
            /* for sms template */
            $name = $post['fullname'];
			$type = 'Customize Existing Design';
			$budget = $post['budget'];
			$email = $post['email'];
			$mobile = $post['mobile'];
			$productsku = $post['product_sku'];
			$message = "Mr. {{*name*}} has a budget of {{*budget*}} and has requested for {{*type*}} with productsku {{*productsku*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
			$template = str_replace("{{*name*}}", $name, $message);
			$template = str_replace("{{*budget*}}", $budget, $template); 
			$template = str_replace("{{*type*}}", $type, $template);
			$template = str_replace("{{*productsku*}}", $productsku, $template); 
			$template = str_replace("{{*mobile*}}", $mobile, $template);
			$template = str_replace("{{*email*}}", $email, $template);   
			$telephone = '9177403012';
			$this->_helper->send_sms($telephone,$template);
			/* for sms template */
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('customizedesign_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo('vaibhavanalytics@gmail.com')
                ->getTransport();
            $transports->sendMessage();
            $this->messageManager->addSuccess('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
			$resultRedirect->setRefererOrBaseUrl();
			return $resultRedirect;
				}
			if($post['type']== 3){
			$emailTempVariables['name'] = $data['fullname'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['details'] = $data['details'];
            /* for sms template */
            $name = $post['fullname'];
			$type = 'Work With Our Designer';
			$email = $post['email'];
			$mobile = $post['mobile'];
			$message = "Mr. {{*name*}} has requested for {{*type*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
			$template = str_replace("{{*name*}}", $name, $message);
			$template = str_replace("{{*type*}}", $type, $template);
			$template = str_replace("{{*mobile*}}", $mobile, $template);
			$template = str_replace("{{*email*}}", $email, $template);   
			$telephone = '9177403012';
			$this->_helper->send_sms($telephone,$template);
			 /* for sms template */
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('workwithdesign_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo('vaibhavanalytics@gmail.com')
                ->getTransport();
            $transports->sendMessage();
            $this->messageManager->addSuccess('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
			$resultRedirect->setRefererOrBaseUrl();
			return $resultRedirect;
				}
        } catch(\Exception $e){
			echo $e->getMessage();
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
			$resultRedirect = $this->resultRedirectFactory->create();
			$resultRedirect->setRefererOrBaseUrl();
			return $resultRedirect;
           // exit;
        }
			
			//======================================== Email Ends =======================================//
			
			
			
		}else{
				
			 		$resultRedirect = $this->resultRedirectFactory->create();
					$resultRedirect->setRefererOrBaseUrl();
					return $resultRedirect; 
		}
	}
	
}