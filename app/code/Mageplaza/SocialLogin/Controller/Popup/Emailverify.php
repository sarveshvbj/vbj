<?php

namespace Mageplaza\SocialLogin\Controller\Popup;

use Magento\Framework\App\Action\Context;
ob_start();

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Emailverify extends \Magento\Framework\App\Action\Action {

    /**
     * @param Context                    $context
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
    Context $context
    ) {
        parent::__construct($context);
    }

    public function execute() {
        // get mobile from form and generate token & store in db als_aent
        $isPost = $this->getRequest()->getParam('email');
        $result['error'] = "true";
        $result['message'] = "";
        $is_unique = "true";
        if ($isPost) {
            $email = "";
            //load customer model with addfiter commandnd
            if (!empty($this->getRequest()->getParam('email'))) {
                $email = $this->getRequest()->getParam('email');
            }

            if ($email != "") {
                $url = \Magento\Framework\App\ObjectManager::getInstance();
                $storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
                $websiteId = $storeManager->getWebsite()->getWebsiteId();
                $customer = $url->create('\Magento\Customer\Model\Customer');
                $customer->setWebsiteId($websiteId);
                $QryResult = $customer->loadByEmail($email);
                if (!empty($QryResult->getId())) {
                    $is_unique = "false";
                    $result['error'] = "false";
                    $result['message'] = "A customer with email: '" . $email . "' already exists in an associated website.";
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
                $result['message'] = "Please enter Email";
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
