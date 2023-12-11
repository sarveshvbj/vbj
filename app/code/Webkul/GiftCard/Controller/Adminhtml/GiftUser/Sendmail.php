<?php
namespace Webkul\GiftCard\Controller\Adminhtml\GiftUser;

class Sendmail extends \Magento\Backend\App\Action{

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Webkul\GiftCard\Helper\Data $helper,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory)
	{

		$this->helper = $helper;
		$this->resultPageFactory = $resultPageFactory;
		parent::__construct($context);
	}

	public function execute(){
		$params = $this->getRequest()->getPost('email');
        //$form = $params['giftuser_form'];
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        exit;
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