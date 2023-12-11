<?php

namespace Magegadgets\Videoform\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Zend\Log\Filter\Timestamp;

class Post extends \Magento\Framework\App\Action\Action
{
	const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
     
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;
     
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
        array $data = []
         
        )
    {
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
         
         
        parent::__construct($context);
         
         
    }
	public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		$post = $this->getRequest()->getPostValue();
		unset($data['submit']);
		unset($post['submit']);
		/*
		echo "<pre>";
		print_r($data);
		echo "<br/>";
		print_r($post);
		echo "</pre>";
		exit();  */
		//var_dump($data);die;
		
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
		if ($data) {

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

			$model = $objectManager->create('Magegadgets\Videoform\Model\Videoform');

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
			
		/*	try
        {
            // Send Mail
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
             
            $sender = [
                'name' => $post['name'],
                'email' => $post['email']
            ];
             
            $sentToEmail = $this->_scopeConfig ->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
             
            $sentToName = $this->_scopeConfig ->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			
            
			echo $sentToEmail;
			echo $sentToName;
			exit(); 
			
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
                    'email'  => $post['email']
                    'mobile'  => $post['mobile'],
                    'language'  => $post['language']
                ])
                ->setFrom($sender)
                ->addTo($sentToEmail,$sentToName)
                //->addTo('owner@example.com','owner')
                ->getTransport();
                 
                $transport->sendMessage();
                 
                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.');
                //$this->_redirect('customemail/index/index');
				//$this->_redirect('');
				$resultRedirect = $this->resultRedirectFactory->create();
				$resultRedirect->setRefererOrBaseUrl();
				return $resultRedirect;
                 
        } catch(\Exception $e){
			echo $e->getMessage();
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
			$resultRedirect = $this->resultRedirectFactory->create();
			$resultRedirect->setRefererOrBaseUrl();
			return $resultRedirect;
           // exit;
        }*/
			
			//======================================== Email Ends =======================================//

			
		}else{
				
			 		$resultRedirect = $this->resultRedirectFactory->create();
					$resultRedirect->setRefererOrBaseUrl();
					return $resultRedirect; 
		}
	}
	
}