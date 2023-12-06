<?php
namespace Magegadgets\Sendinquiry\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend\Log\Filter\Timestamp;

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

			$model = $objectManager->create('Magegadgets\Sendinquiry\Model\Sendinquiry');

			$model->setData($data);
			
			try {
				
				$model->save();

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
            /* for sms template*/
		   $name = $post['name'];
           $email = $post['emails'];
           $type = 'International Shipment';
           $productname = $post['productname'];
           $producturl = $post['producturl'];
           $productcode =$post['productcode'];
           $contact = $post['contact'];
           $area = $post['area'];
		   $message = "Mr. {{*name*}} has requested for {{*type*}} and for product name  {{*productname*}} and product code {{*productcode*}} to contact this customer please call on {{*contact*}} or send an email on {{*email*}}.";
		   $template = str_replace("{{*name*}}", $name, $message);
		   $template = str_replace("{{*productname*}}", $productname, $template); 
		   $template = str_replace("{{*type*}}", $type, $template);
		   $template = str_replace("{{*productcode*}}", $productcode, $template);
		   $template = str_replace("{{*email*}}", $email, $template);
		   $template = str_replace("{{*contact*}}", $contact, $template);
		   $telephone = '0000000000';
		   $templateId="1407160957201615801";
		   $this->_helper->send_sms($telephone,$template,$templateId);
		/* for sms template*/
            // Send Mail
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
             
            $sender = [
                'name' => $post['name'],
                'email' => $post['emails']
            ];
             
            $sentToEmail = $this->_scopeConfig ->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
             
            $sentToName = $this->_scopeConfig ->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
            /*
			echo $sentToEmail;
			echo $sentToName;
			exit(); */
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier('customemail_email_template')
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )
                ->setTemplateVars([
                    'name'  => $post['name'],
                    'email'  => $post['emails'],
                    'productname' => $post['productname'],
                    'producturl' => $post['producturl'],
                    'productcode' => $post['productcode'],
                    'contact' => $post['contact'],
                    'area' => $post['area']
                ])
                ->setFrom($sender)
                //->addTo($sentToEmail,$sentToName)
                ->addTo('vaibhavanalytics@gmail.com','Sarvesh Tiwari')
                ->getTransport();
                $transport->sendMessage();
                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
                //$this->_redirect('customemail/index/index');
				//$this->_redirect('*/*/');
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