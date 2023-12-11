<?php

namespace Vaibhav\GoldRate\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;
use Vbj\Goldrateform\Model\VaibhavGoldrateFormFactory;
use Magento\Framework\Controller\ResultFactory;

class Goldrateform extends Action
{
    protected $resultPageFactory;
    protected $vaibhavGoldrateFormFactory;
     protected $_helper;

    public function __construct(Context $context, PageFactory $resultPageFactory,VaibhavGoldrateFormFactory $vaibhavGoldrateFormFactory,\Iksula\Complaint\Helper\Data $_helper)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->vaibhavGoldrateFormFactory = $vaibhavGoldrateFormFactory;
        $this->_helper=$_helper;
    }

    public function execute()
    {

        /*return $this->resultPageFactory->create();*/
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try{

            $request = $this->getRequest()->getParams();           
            $email = $request['email'];
            $data = array();
            if($request){
                $data['customer_name'] = $request['customerName'];
                $data['customer_email'] = $request['email'];
                $data['customer_area'] = $request['area'];
                $data['customer_mobile'] = $request['mobilenumber'];
                $model = $this->vaibhavGoldrateFormFactory->create();
                $model->setData($data)->save();
                $this->messageManager->addSuccessMessage(__("Dear ". ' '. $request['customerName']. ', '. "Thank you for subscriber."));
            }
            //return $this->resultPageFactory->create();

        }catch (\Exception $e){
            $this->messageManager->addExceptionMessage($e, __('We can\'t submit your request, Please try again.'));
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            //return $this->resultPageFactory->create();
        }
         /* for sms template*/
           $name = $request['customerName'];
           $type = 'Subscribe Gold Rate';//$data['email'];
           $area = $request['area'];
           $email = $request['email'];
           $mobile = $request['mobilenumber'];
          
           $message = "Mr. {{*cust_name*}} has requested for {{*type*}} from {{*area*}} area to contact this customer please call on {{*mobile*}} or send an email on {{*email*}}.";
           $template = str_replace("{{*cust_name*}}", $name, $message);
           $template = str_replace("{{*type*}}", $type, $template); 
           $template = str_replace("{{*area*}}", $area, $template);
           $template = str_replace("{{*mobile*}}", $mobile, $template);
           $template = str_replace("{{*email*}}", $email, $template);
           
           $telephone = '9177403012';
           $templateId="1407160957224288696";
           $this->_helper->send_sms($telephone,$template,$templateId);
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;

    }
}