<?php

namespace Magegadgets\Videoform\Controller\Index;

class Tryon extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
        
/*    $isPost = $this->getRequest()->getPost();
    $is_unique = "true";
    if ($isPost) {
        if (!empty($this->getRequest()->getPost('style'))) {
                $is_unique = "true";
            $style = $this->getRequest()->getPost('style');
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
                ->addFieldToFilter('sku', array('in' => $skus))
                ->addAttributeToFilter('style', $style)
                ->load();
                $productId = array();
            foreach ($collection as $product){ 
                $productId[] = $product->getSku();
            }
                echo json_encode($productId);
            }else{
                echo $is_unique = "false";
                $style = 'style';
            }
    }*/
    }
}