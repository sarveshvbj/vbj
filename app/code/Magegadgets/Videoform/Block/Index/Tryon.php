<?php
namespace Magegadgets\Videoform\Block\Index;

class Tryon extends \Magento\Framework\View\Element\Template {
    
    protected $_productRepository;
    private $_objectManager;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context, 
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        array $data = []
        ) {
        $this->_productRepository = $productRepository;
        $this->_objectManager = $objectmanager;
        parent::__construct($context, $data);

    }
    
    protected function _prepareLayout()
    {
         $this->pageConfig->getTitle()->set(__('TryOn'));
        
        if ($this->getTestCollection()) {
	        $pager = $this->getLayout()->createBlock(
	            'Magento\Theme\Block\Html\Pager',
	            'videoform_index_tryon.pager'
	        )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
	            $this->getTestCollection()
	        );
	        $this->setChild('pager', $pager);
	        $this->getTestCollection()->load();
	    }
        return parent::_prepareLayout();
    }
    
      public function getTestCollection()
    {
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 20;
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $camwearaTable = $resource->getTableName('camweara_table');
        $skuTryon = "SELECT sku FROM camweara_table";
        $skuTryonresults = $connection->fetchAll($skuTryon);
        foreach($skuTryonresults as $skuTryonresult){
        $skus[] = $skuTryonresult['sku'];
        }
    $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
    $collection = $productCollection->addAttributeToSelect('*')
                ->addFieldToFilter('sku', array('in' => $skus));
    $collection->setOrder('created_at', 'desc');
    $collection->setPageSize($pageSize);
    $collection->setCurPage($page);
 
        return $collection;
    }
 
    public function getPagerHtml()
	{
	    return $this->getChildHtml('pager');
	}
    
}