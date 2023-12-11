<?php
namespace Als\Testimonials\Controller\Postemail;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;
ob_start();
/**
 * Class Check
 * @package MageArray\CheckDelivery\Controller\Postcode
 */
class Check extends Action
{
    /**
     * @var ProductModel
     */
    protected $_objectManager;
    /**
     * @var DataHelper
     */
    protected $_messageManager;
    
    protected $_logLoggerInterface;
    
    protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_helper;

    /**
     * Check constructor.
     * @param Context $context
     * @param ProductModel $productModel
     * @param DataHelper $helper
     */
    public function __construct(
        Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
         \Iksula\Complaint\Helper\Data $_helper
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectManager;
        $this->_messageManager = $messageManager;
        $this->_logLoggerInterface = $loggerInterface;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_helper=$_helper;
    }

    /**
     *
     */
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        $post = $this->getRequest()->getPostValue();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $logo_url = $baseUrl.'/pub/media/email/logo/stores/1/logo_email.png';
        
        /*echo '<pre>';
        print_r($data);
        echo  '</pre>';
        die();*/
        $datavalue['product_sku'] = $data['product_sku'];
        $datavalue['product_name'] = $data['product_name'];
        $datavalue['product_price'] = $data['product_price'];
        $datavalue['customer_name'] = $data['customer_name'];
        $datavalue['customer_email'] = $data['customer_email'];
        $datavalue['customer_mobile'] = $data['customer_mobile'];
        $datavalue['logo_url'] = $logo_url;
      /*  echo '<pre>';
        print_r($datavalue);
        echo  '</pre>';
        exit;*/
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($data) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $models = $this->_objectManager->create('Als\Testimonials\Model\Testimonials');
            $model = $this->_objectManager->create('Als\Testimonials\Model\Testimonials')->getCollection()
                                                  ->addFieldToFilter('customer_email', array('like' =>$data['customer_email']));
            $emailvalidate = $model->count();
        try {  
           /* if(!$emailvalidate && $emailvalidate ==0){*/
                $models->setData($datavalue);
                $models->save();
                //$this->messageManager->addSuccess(__('The Notify details has been submitted successfully. We will contact you when will product in stock.'));
                //$resultRedirect->setUrl($this->_redirect->getRefererUrl());
                //return $resultRedirect;

           /* }
           else{
                    $this->messageManager->addError(__('This Email Id is alreday notify for this product.'));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    $resultRedirect->setRefererOrBaseUrl();
                    return $resultRedirect; 
           }*/  
               } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError($e->getMessage());
                    $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                } catch (\RuntimeException $e) {
                    $this->messageManager->addError($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
                   $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                }
            	//======================================== Email starts =======================================//
			
		try
        {
            
            /*$emailTempVariables['name'] = $data['fullname'];
            $emailTempVariables['email'] = $data['email'];
            $emailTempVariables['mobile'] = $data['mobile'];
            $emailTempVariables['language'] = $data['language'];
            $emailTempVariables['time'] = $data['takedate'];*/
             /* for sms template*/
           $name = $data['customer_name'];
		   $type = 'Price Notification';
		   $email = $data['customer_email'];
		   $mobile = $data['customer_mobile'];
		   $product_name = $data['product_name'];
		   $product_sku = $data['product_sku'];
		   $product_price = $data['product_price'];
		   $message = "Mr. {{*name*}} has requested for {{*type*}} and for {{*product_name*}} and {{*product_sku*}} to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
		   $template = str_replace("{{*name*}}", $name, $message);
		   $template = str_replace("{{*product_name*}}", $product_name, $template);
		   $template = str_replace("{{*product_sku*}}", $product_sku, $template); 
		   $template = str_replace("{{*type*}}", $type, $template);
		   $template = str_replace("{{*mobile*}}", $mobile, $template);
		   $template = str_replace("{{*email*}}", $email, $template);
		   $telephone = '9177403012';
		   $this->_helper->send_sms($telephone,$template);
			/* for sms template*/
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($datavalue);
            //$store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('pricenotify_email_template')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo($data['customer_email'])
                ->getTransport();
            $transports->sendMessage();
            
            /**/
				$this->messageManager->addSuccess(__('The Notify details has been submitted successfully. We will contact you when will product in stock.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                return $resultRedirect;
                 
        } catch(\Exception $e){
			//echo $e->getMessage();
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
			$resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
            return $resultRedirect;
        }
			
			//======================================== Email Ends =======================================//

			
		}else{
				
			 		$resultRedirect = $this->resultRedirectFactory->create();
                    $resultRedirect->setPath('thank-you'); // set this path to what you want your customer to go
                    return $resultRedirect;
		}
}
}