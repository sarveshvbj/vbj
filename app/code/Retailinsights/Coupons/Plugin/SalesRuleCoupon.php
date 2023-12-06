<?php
namespace Retailinsights\Coupons\Plugin;

class SalesRuleCoupon{

    protected $_logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
        ){
            $this->_logger = $logger;
    }

    public function beforeLoad(\Magento\SalesRule\Model\Coupon $coupon, $modelId, $field = null)
    {
        //if model is loaded by code search for your matching data in your custom code
        if ($field == 'code'){
            $replacedCode = $this->getYourMatchingCode($modelId);
             
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/PRAsale22s.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('this'); 

            
            if ($replacedCode !== null){
                $modelId = $replacedCode;
            }
        }
        return [$modelId, $field];
    }

    private function getYourMatchingCode($originalCode)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product_discamt = 0;
        $objrules = $objectManager->create('Magento\SalesRule\Model\RuleFactory')->create();
        // $rules = $objrules->getCollection();
        $rules = $objrules->getCollection()
        ->addFieldToFilter('code', $originalCode);

        
        $phone_number='';
        foreach ($rules as $tmprule) {
            $phone_number = $tmprule['phone_number'];
        }
       

	
		
        $replacedCode = null;
        //implement your logic here and set $replacedCode according to your match

        return $replacedCode;
    }
}
?>
