<?php

namespace Mageplaza\SocialLogin\Controller\Popup;

use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\App\Action\Context;
use Vaibhav\Otp\Model\OtpFactory;
ob_start();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Codeverify extends \Magento\Framework\App\Action\Action
{
    protected $jsonHelper;
/**
    * @var \Als\Agent\Model\AgentFactory
    */
   //protected $_agentFactory;
    /**
     * @param Context                    $context
     * @param JsonHelper                 $jsonHelper
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        OtpFactory $otpFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonHelper   
        
    ) {
         parent::__construct($context);
        $this->jsonHelper          = $jsonHelper;
        $this->_otpFactory = $otpFactory;
       
    }
		
    public function execute()
    {
		// Check entered OTP is correct den delete entry from als_aent and update Aent code for session user 
		$isPost = $this->getRequest()->getPost();
		//print_r($isPost);
		//die();
        if ($isPost) {
			
			// session data 
			$mobile = "";
			
			if(!empty($this->getRequest()->getPost('telephone')))
              $mobile = $this->getRequest()->getPost('telephone');
    		
			if(!empty($this->getRequest()->getPost('mobile')) and $mobile == "")
                  $mobile = $this->getRequest()->getPost('mobile');
				  
			$token = $this->getRequest()->getPost('otp');
						
			$otpCollection = $this->_otpFactory->create()->getCollection()->addFilter('mobile',  $mobile)->addFilter('otp',  $token);
			//echo "Query -".$agentCollection->getSelect();
			$found = $otpCollection->getSize();
		
			if($found > 0) {
				//var_dump($agentCollection->getData());
				$agentData = $otpCollection->getData();
				
				$deleteId = $agentData[0]['id'];
				
				// verified den delete entry n update customer db 
				$agentModel = $this->_otpFactory->create();
				$agentModel->load($deleteId);
				
			   		
				try{
	 				// delete opt entry 
					$agentModel->delete();
					
					$result['success'] = true;
					$result['message'] = "Verified";
				}
				catch(\Exception $e) {
						//print_r( $e->getMessage());
					$result['error']     = true;
					$result['message'] =  $e->getMessage();
				}

			}else {
				// verification not matced error 
				$result['error'] = true;
				$result['message'] = "Invalid Verification Code.";
			}
		   echo json_encode($result);
		}
    }
}

