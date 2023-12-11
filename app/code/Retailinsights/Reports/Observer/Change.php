<?php

namespace Retailinsights\Reports\Observer;

class Change implements \Magento\Framework\Event\ObserverInterface
{
	public $eventComplete = false;
  protected $_quotesFactory;
   private $quoteItemFactory;
   private $customerRepository;
   private $addressRepository;

	public function __construct(
		\Magento\Reports\Model\ResourceModel\Quote\CollectionFactory $quotesFactory,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
	){
		$this->_quotesFactory = $quotesFactory;
         $this->quoteItemFactory = $quoteItemFactory;
         $this->customerRepository = $customerRepository;
         $this->addressRepository = $addressRepository;
	}


    public function execute(\Magento\Framework\Event\Observer $observer)
    {

      


        if($this->eventComplete){return;}
        $this->eventComplete = true;

    	$collection = $this->_quotesFactory->create();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $renderBlock = $objectManager->create('Retailinsights\Reports\Block\Adminhtml\Shopcart\Abandoned\Grid');
        $coll = $renderBlock->abandonedCollection();

    	 // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    	 // $getBlock = $this->getLayout()->createBlock("Retailinsights\Reports\Block\Adminhtml\Shopcart\Abandoned\Grid");
		// $getBlock->getAll();

    	 // $blockObj = $this->$objectManager->get("Retailinsights\Reports\Block\Adminhtml\Shopcart\Abandoned\Grid");

    	 // $blockObj->_prepareCollection();

        $prepareCollection = $collection->addFieldToFilter('customer_group_id', 1)->addFieldToFilter('is_active', 1);


         foreach ($prepareCollection as $ky => $value) {
            $telephone="";
            try {
            $customer = $this->customerRepository->getById($value->getCustomerId());
            $billingAddressId = $customer->getDefaultBilling();
            $shippingAddressId = $customer->getDefaultShipping();
            $billingAddress = $this->addressRepository->getById($billingAddressId);
            $telephone = $billingAddress->getTelephone();
    } catch (\Magento\Framework\Exception\NoSuchEntityException $noSuchEntityException) {
        $telephone="";
    }
            $carts = $this->quoteItemFactory->create();
            $cartCollection = $carts->getCollection()->addFieldToFilter('quote_id',$value->getEntityId());

            $item_id="";
            $quote_id="";
            $name = "";
            $sku = "";
            foreach ($cartCollection->getData() as $quoteItem) {
            
           // $logger->info($quoteItem);
   
               $item_id = $quoteItem['item_id'];
                $quote_id = $quoteItem['quote_id'];

                $name .= $quoteItem['name'];
                $name .= ', ';

                $sku .= $quoteItem['sku'];
                $sku .= ', ';

                    if($quoteItem['quote_id'] == $value->getEntityId()){

                    /*  $logger->info($quoteItem['quote_id']);
                        $logger->info($value->getEntityId());
                        $logger->info($name);
                        $logger->info($sku);*/

                
                    }
           
            }
            // $value->setData('name',$name);
            // $value->setData('sku',$sku);

            // $resultCollection = clone $value;
           
             // $logger->info($quote_id);  
            // $logger->info($name); 
            // $logger->info($sku); 

            // $logger->info($collection->getData());

            if($quote_id>0){

      
        $value->load($quote_id);
                        // $collection->setEntityId($quote_id);
                        // $logger->info($postUpdate->getData());
                        $value->setName($name);
                        $value->setTelephone($telephone);
                        $value->setSku($sku);
                        $value->save();

                        
            }

 
        }




            	
		 // $logger->info($collection->getData()); 

		     
       return $this;
    }
}