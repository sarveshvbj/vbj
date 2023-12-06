<?php
namespace Retailinsights\Settings\Block;
class Display extends \Magento\Framework\View\Element\Template
{
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
		array $data = []
	)
	{
		$this->_productCollectionFactory = $productCollectionFactory;
		parent::__construct($context, $data);
	}

	public function getProducts()
	{
		$collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('attribute_set_id', 12);
       
        return $collection;
	}
	public function getDiamond()
	{
		$collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('attribute_set_id', 12);
        // if(isset($_SESSION["diamond_settings_diamond"])){
        // 	$collection->addAttributeToFilter('entity_id', $_SESSION["diamond_settings_diamond"]);
        // }else{
        // 	$collection = '';
        // }
    	
    	$diamond =array();
    	
		foreach ($collection as $key => $value) {
			$diamond = array(
				'id' => $value->getEntityId(),
				'brands_name' => $value->getBrandsName(),
				'price' => $value->getPrice()
			);

		}
		
        return $diamond;
	}
}

?>
