<?php
namespace Webkul\GiftCard\Controller\Adminhtml\GiftUser;

class Save extends \Magento\Backend\App\Action{

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Webkul\GiftCard\Helper\Data $helper,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{

		$this->helper = $helper;
		$this->_storeManager = $storeManager;
		$this->_transportBuilder = $transportBuilder;
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function execute(){
		$params = $this->getRequest()->getParams();
        $form = $params['giftuser_form'];
       /* echo '<pre>';
        print_r($form);
        echo '</pre>';*/
        $status = $form['used'];
        $customer_email = $form['from'];
        $email = $form['email'];
        $code = $form['code'];
        $price = $form['price'];
        $description = $form['description'];
        if($status == 1 && $status !=NULL){
            $emailTemplateVariable = array();
                $emailTempVariables['customer_email'] = $customer_email;
                $emailTempVariables['email'] = $email;
                $emailTempVariables['code'] = $code;
                $emailTempVariables['price'] = $price;
                $emailTempVariables['description'] = $description;
                //print_r($emailTempVariables);
            $postObjects='';
            $postObjects = new \Magento\Framework\DataObject();
            $postObjects->setData($emailTempVariables);
            $store = $this->_storeManager->getStore()->getId();
            $transports = $this->_transportBuilder
                ->setTemplateIdentifier('giftcard_emaill_gift_notification_mail')
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => @$postObjects])
                ->setFrom('general')
                ->addTo($email)
                ->getTransport();
            $transports->sendMessage();
        }
        //exit;
		$response = $this->helper->saveGiftVoucher($form);
		if($response){
			if($response['is_error']){
				$this->messageManager->addError(__($response['message']));
			}else{
				$this->messageManager->addSuccess(__($response['message']));
			}
	        return $this->_redirect($response['return_url']);
		}
        return $this->_redirect('*/*/index');
	}
}