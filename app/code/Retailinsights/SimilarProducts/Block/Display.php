<?php
namespace Retailinsights\SimilarProducts\Block;
class Display extends \Magento\Framework\View\Element\Template
{
	protected $_postFactory;
	protected $_storeManager;
	protected $_registry;
	public function __construct(
		\Magento\Framework\Registry $registry,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,        
        array $data = []
	)
	{
		$this->_registry = $registry;
		$this->_storeManager = $storeManager;
		$this->_productCollectionFactory = $productCollectionFactory;    
        parent::__construct($context, $data);
	}
	 public function getCurrentCategory()
    {   
    	
    	$category = '';

    	$_category = $this->_registry->registry('current_category');
    	if($_category!=''){
    		$category = $_category->getEntityId();
    		
    	}else{
    		$category = '';
    	}
        return $category; 
    }

	public function getMediaUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
	}
	public function getBaseUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}
	public function sayHello()
	{
		return __('Hello World');
	}

	public function test($current_category)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        if($current_category!=''){
	        $collection->addCategoriesFilter(['in' => $current_category]);
        }
        $collection->setPageSize(6); // fetching only 3 products


        return $collection;
    }

    public function getProductCollection($current_category)
    {

    	$cacheId =$current_category;
		$collectionData='';
        if ($cacheData = $this->_cache->load($cacheId)) {
            $collectionData = unserialize($cacheData);
        }else{

	      	$collectionData = json_decode($collectionData, TRUE);
			$collection = $this->_productCollectionFactory->create();
			$collection->addAttributeToSelect('*');
			if($current_category!=''){
		        $collection->addCategoriesFilter(['in' => $current_category]);
	        }
	        			$collection = $this->_productCollectionFactory->create();
			$collection->addAttributeToSelect('*');
			if($current_category!=''){
		        $collection->addCategoriesFilter(['in' => $current_category]);
	        }
	        $collection->setPageSize(6);

			$productData= array();
			foreach ($collection as $key => $value) {
				// code...
				$metadata['name'] = $value['name'];
				$metadata['price'] = $value['price'];
				$metadata['url_key'] = $value['url_key'];
				$metadata['small_image'] = $value['small_image'];
				$productData[$key] = $metadata;
			}
			$collectionData = json_encode($productData);
	        $this->_cache->save(serialize($collectionData), $cacheId, [], 86400);
        }

        $collectionData = json_decode($collectionData, TRUE);

		return $collectionData;

    }
}
