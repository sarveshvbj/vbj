<?php
namespace Vaibhav\Tryathome\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend\Log\Filter\Timestamp;
ob_start();
class Index  extends \Magento\Framework\App\Action\Action {

    const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
    
    protected $_pageFactory;
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
    protected $_helper;
    
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Iksula\Complaint\Helper\Data $_helper
        ) {
        $this->_pageFactory = $pageFactory;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
        $this->_helper=$_helper;
        parent::__construct($context);

    }

    public function execute() {
    
    $data = $this->getRequest()->getPostValue();
    $post = $this->getRequest()->getPostValue();   
    //return $this->_pageFactory->create();
    $datavalue['sname'] = $data['cust_name'];
    $datavalue['area'] = $data['area'];
    $datavalue['email'] = $data['email'];
    $datavalue['mobile'] = $data['mobile'];
    $datavalue['source'] = $data['source'];
    		if ($data) {
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$model = $objectManager->create('Vaibhav\Tryathome\Model\Tryathome');
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
            $emailTempVariables['cust_name'] = $data['cust_name'];
            $emailTempVariables['area'] = $data['area'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['source'] = $data['source'];
             /* for sms template*/
           $name = $data['cust_name'];
		   $type = $data['source'];
		   $area = $data['area'];
		   $email = $data['email'];
		   $mobile = $data['mobile'];
		  
		   $message = "Mr. {{*cust_name*}} has requested for {{*type*}} and for {{*area*}} area to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
		   $template = str_replace("{{*name*}}", $name, $message);
		   $template = str_replace("{{*type*}}", $type, $template); 
		   $template = str_replace("{{*area*}}", $area, $template);
		   $template = str_replace("{{*mobile*}}", $mobile, $template);
		   $template = str_replace("{{*email*}}", $email, $template);
		   
		   $telephone = '9177403012';
		   $this->_helper->send_sms($telephone,$template);
			/* for sms template*/
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            //$store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('tryathome_email_template')
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