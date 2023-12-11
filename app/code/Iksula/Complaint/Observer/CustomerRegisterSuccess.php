<?php

namespace Iksula\Complaint\Observer;

use Magento\Framework\Event\ObserverInterface;


/**
 * AdminhtmlCustomerSaveAfterObserver Observer.
 * upon signup customer/doctor/Chemist can enter the Agent code
  if the agent code is entered then on approval Agent will get a registeration refferel commission.
  check if user has agent code and is approved and agent commision entry is there or not

 */
class CustomerRegisterSuccess implements ObserverInterface {

    
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
        
       $accountController = $observer->getAccountController();
        $customer = $observer->getCustomer();
        $request = $accountController->getRequest();
        $customer_email = $customer->getEmail();
        /*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/Customer_Register.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($customer_email);*/
        $customer_name = $customer->getFirstname().' '.$customer->getLastname();
        $customer_number = $request->getParam('mobile');
         if($customer_number) {
             /*$logger->info("inside Mobile number Fields");*/
             $customer->setCustomAttribute('mobile', $customer_number);
             $this->customerRepository->save($customer);
             /*$logger->info($customer_number);*/
         }
        // if($customer_email && $customer_email !=NULL){
        //     $emailTemplateVariable = array();
        //         $emailTempVariable['customer_name'] = $customer_name;
        //     $postObjects='';
        //     $postObjects = new \Magento\Framework\DataObject();
        //     $postObjects->setData($emailTempVariable);
        //     $store = $this->_storeManager->getStore()->getId();
        //     $transports = $this->_transportBuilder
        //         ->setTemplateIdentifier('account_created')
        //         ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
        //         ->setTemplateVars(['data' =>$postObjects])
        //         ->setFrom('general')
        //         ->addTo($customer_email)
        //         ->getTransport();
        //     $transports->sendMessage();
        //   }
       

        
    }
   

    /**
     * If usertype is agent and agent is approved and agent code is not assigned 
     * then generate the agent code for the agent and send an email and sms
     * save in attribute as well as new agent table
     */
 
  
   
}