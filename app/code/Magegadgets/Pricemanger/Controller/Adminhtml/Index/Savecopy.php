<?php
namespace Magegadgets\Pricemanger\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    public function execute()
    {
		$data = $this->getRequest()->getPostValue();
		$resultRedirect = $this->resultRedirectFactory->create();
		
        if ($data) {
			unset($data['form_key']);
			/*print "<pre>";
			print_r($data);*/
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			
			$K24 = $data['24K'];
			$K22 = $data['22K'];
			$K18 = $data['18K'];
    		$K14 = $data['14K'];
    		$hundred = $data['hundred'];
    		$ninety_two = $data['ninety-two'];
    		$ninety_one = $data['ninety-one'];
    		$p_hundred = $data['p-hundred'];
    		$ninety_nine = $data['ninety-nine'];
    		$ninety_five = $data['ninety-five'];
    		$usd = $data['usd'];
    		$tax_customer = $data['tax-customer'];
    		$tax_business = $data['tax-business'];
			
			$UpdateSql = "UPDATE `pricemanager` SET `24K` = '$K24' , `22K` = '$K22' , `18K` = '$K18' , `14K` = '$K14' , `hundred` = '$hundred' , `ninety_two` = '$ninety_two' , `ninety_one` = $ninety_one , `p_hundred` = $p_hundred , `ninety_nine` = $ninety_nine , `ninety_five` = $ninety_five , `usd` = $usd , `tax_customer` = $tax_customer , `tax_business` = $tax_business  WHERE `pricemanager`.`id` = 1;";  
			
            $resp = $connection->query($UpdateSql);  
			
			
			$this->messageManager->addSuccess(__('Price was successfully updated.'));
			return $resultRedirect->setPath('*/*/index');
		}
	}
}

?>