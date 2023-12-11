<?php

namespace Mageplaza\SocialLogin\Controller\Popup;

use Magento\Framework\App\Action\Context;
ob_start();

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Phoneverify extends \Magento\Framework\App\Action\Action { 

	protected  $_resource;
    /**
     * @param Context                    $context
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
    Context $context,
	\Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct($context);
		$this->_resource = $resource;
    }

    public function execute() {
        // get mobile from form and generate token & store in db als_aent
        $isTelephone = $this->getRequest()->getParam('telephone');
		  $isMobile = $this->getRequest()->getParam('mobile');
        $result['error'] = "true";
        $result['message'] = "";
        $is_unique = "true";
		
        if ($isTelephone or $isMobile) {			
            $mobile = "";
            //load customer model with addfiter commandnd
           if(!empty($this->getRequest()->getParam('telephone'))){
                 $mobile = $this->getRequest()->getParam('telephone');				
		    }
    
			if(!empty($this->getRequest()->getParam('mobile')) and $mobile == "")
                  $mobile = $this->getRequest()->getParam('mobile');
			

            if($mobile !="")  {
               $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
				$bind = ['mobile' => $mobile];
				$select = $connection->select()->from(
					'customer_entity_varchar',
					['value']
				)->where(
					'value = :mobile'
				);
				$bind['attribute_id'] = 201;// for server its 188 n for local its 190
				$select->where('attribute_id = :attribute_id');
			
				$Qryresult = $connection->fetchOne($select, $bind);
				if ($Qryresult) {
					 $is_unique = "false";
				 	$result['error']     = true;
					$result['message'] =  "A customer with the same Mobile already exists in an associated website.";					
				}
                else
                {
                     $is_unique = "true";
                     $result['error'] = "true";
                     $result['message'] = "";
                     
       
                }
            } else {
                $is_unique = "false";
                $result['error'] = "false";
                $result['message'] = "Please enter Mobile";
            }
            echo $is_unique;
        }
        else
        {
            $result['error'] = "false";
            $result['message'] = "Invalid request";
            echo "false";
        }
    }

}
