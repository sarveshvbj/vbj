<?php

namespace Iksula\Complaint\Observer;

use Magento\Framework\Event\ObserverInterface;



/**
 * AdminhtmlCustomerSaveAfterObserver Observer.
 * upon signup customer/doctor/Chemist can enter the Agent code
  if the agent code is entered then on approval Agent will get a registeration refferel commission.
  check if user has agent code and is approved and agent commision entry is there or not

 */
class CustomerAccountForgotPassword implements ObserverInterface {

    
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    //protected $_transportBuilder;
    protected $scopeConfig;
    protected $_customerFactory;
    protected $_helper;
    protected $customerRepository;


    public function __construct(
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Framework\App\RequestInterface $request,
            \Iksula\Complaint\Helper\Data $_helper,
            \Magento\Customer\Model\CustomerFactory $customerFactory,
            \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
    ) {
        
        $this->scopeConfig = $scopeConfig;
        $this->_request = $request;
        $this->_helper=$_helper;
        $this->_customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * admin customer save after event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        
       $post = $post = $this->_request->getPost();
       $email = $post['email'];
        $customer = $this->_customerFactory->create();
        $customer->setWebsiteId(1);
        $customer->loadByEmail($email);
        $data = $customer->getData();
        if($customer->getEntityId()){
        $cuid = $customer->getEntityId();
        $token = $customer->getRpToken();
        $customeraddress = $this->customerRepository->getById($customer->getEntityId());
        /** @var \Magento\Customer\Api\Data\AddressInterface $address */
        foreach ($customer->getAddresses() as $address) {
            $mobile = $address->getTelephone();
        }
        if(isset($mobile) && $mobile!=NULL){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $url ="".$baseUrl."/customer/account/createPassword/?id=".$cuid."&token=".$token."";
        $message = "Click {{*url*}} to reset the password of your VaibhavJewellers account";
        $template = str_replace("{{*url*}}", $url, $message);
        $templateId="1407160957255594162";
        $this->_helper->send_sms($mobile,$template,$templateId);
        }
        }
    }
}