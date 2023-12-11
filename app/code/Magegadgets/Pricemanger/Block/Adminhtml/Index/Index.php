<?php

namespace Magegadgets\Pricemanger\Block\Adminhtml\Index;

class Index extends \Magento\Backend\Block\Widget\Container
{

    protected $_backendUrl;
	protected $_resource;
	
	
    public function __construct(
		\Magento\Backend\Model\UrlInterface $backendUrl,
		\Magento\Backend\Block\Widget\Context $context,
		\Magento\Framework\App\ResourceConnection $resource,
		array $data = [])	    
    {
		$this->_backendUrl = $backendUrl;
		$this->_resource = $resource;
        parent::__construct($context, $data);
    }
	
	
	
	public function getFormAction()
    {
         $url = $this->_backendUrl->getUrl("*/*/save");
        return $url;
    }
	
	public function getPriceCollection(){
		$connection = $this->_resource->getConnection();
		$option_values = $connection->fetchRow("SELECT * FROM `pricemanager` WHERE `id` = 1");
		return $option_values;
	}


}
