<?php
namespace Magegadgets\Personalizejewellery\Controller\Index;

class Getimage extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
		
		$productSku = $this->getRequest()->getPostValue('productsku');
		$obj = \Magento\Framework\App\ObjectManager::getInstance();
		$productRepository =$obj->get('\Magento\Catalog\Model\ProductRepository');
		$_product = $productRepository->get($productSku);
		$store = $obj->get('Magento\Store\Model\StoreManagerInterface')->getStore();
		echo $imageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $_product->getImage();
	}
	
}