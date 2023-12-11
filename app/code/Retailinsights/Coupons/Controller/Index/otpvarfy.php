<?php
namespace Retailinsights\Coupons\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
class otpvarfy extends \Magento\Framework\App\Action\Action
{
	protected $_pageFactory;
	protected $resultFactory;

	public function __construct(
		\Magento\Framework\View\Result\PageFactory $resultFactory,
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory)
	{
		$this->resultFactory = $resultFactory;
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute()
	{	$validation ='false';
		$userMobile = $this->getRequest()->getPost('userMobile');
		$userOtp = $this->getRequest()->getPost('otp');
		if(isset($_SESSION['opt_coupon']) && isset($_SESSION['opt_mob'])){
			if($userOtp == $_SESSION['opt_coupon'] && $userMobile==$_SESSION['opt_mob']){
				$validation = 'true';
				$_SESSION['opt_coupon']='';
				$_SESSION['opt_mob']='';
			}
		}
		$resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        	$resultJson->setData($validation);
        	return $resultJson;

	}
}