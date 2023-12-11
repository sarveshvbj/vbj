<?php

namespace Magegadgets\Videoform\Controller\Index;
use Magento\Framework\App\Action\Context;
ob_start();

class Tryit extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
    $isPost = $this->getRequest()->getPost();
    $is_unique = "true";
    $productIdStyle = array();
    $productIdWeight = array();
    $productIdCat = array();
    if ($isPost){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $camwearaTable = $resource->getTableName('camweara_table');
        $skuTryon = "SELECT sku FROM camweara_table";
        $skuTryonresults = $connection->fetchAll($skuTryon);
        foreach($skuTryonresults as $skuTryonresult){
            $skus[] = $skuTryonresult['sku'];
            }
           if (!empty($this->getRequest()->getPost('category')) && empty($this->getRequest()->getPost('style'))){
                $category = $this->getRequest()->getPost('category');
               /* $cateinstance = $objectManager->create('Magento\Catalog\Model\CategoryFactory');
                $collection = $cateinstance->create()->load($category)->getProductCollection()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('sku', array('in' => $skus));
                    $productIdCat = array();
                foreach ($collection as $product){ 
                    $productIdCat[] = $product->getSku();
                }*/
                $skuTryons = "SELECT sku FROM camweara_table where category=$category";
                $skuTryonresultss = $connection->fetchAll($skuTryons);
                $skuss = array();
                foreach($skuTryonresultss as $skuTryonresults){
                 $skuss[] = $skuTryonresults['sku'];
                 }
              $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                /** Apply filters here */
                $collection = $productCollection->addAttributeToSelect('*')
                            ->addFieldToFilter('sku', array('in' => $skuss));
                $collection->setOrder('created_at', 'desc');
                /*$collection->setPageSize(15);
                $collection->setCurPage(1);*/
            foreach ($collection as $product){ 
                $productUrl =  $product->getProductUrl(); 
                $productName = $product->getName();
                $productImage = $product->getSmallImage();
                $productprice = $product->getPrice();
                $productId = $product->getId();
                $product       = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
                $store         = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
                $imageUrl      = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                $mediaUrl      =      $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            ?>
            <li class="item product product-item" id="<?php echo $product->getSku();     ?>">
            <div class="product-item-info" data-container="product-grid">
            <div class="product-shop-top">
            <a href="<?php echo $productUrl;?>" class="product photo product-item-photo" tabindex="-1">
            <span class="product-image-container em-alt-hover" style="width:280px;">
            <span class="product-image-wrapper" style="padding-bottom: 100%;">
            <img class="product-image-photo" src="<?php echo $imageUrl;?>" width="280" height="280" alt="<?php echo $productName;?>"></span>
            </span>
            <span class="product-image-container em-alt-org" style="width:280px;">
            <span class="product-image-wrapper" style="padding-bottom: 100%;">
            <img class="product-image-photo" src="<?php echo $imageUrl;?>" width="280" height="280" alt="<?php echo $productName;?>"></span>
            </span>
            </a>
            <div class="em-element-display-hover bottom">
            </div>
            </div>
        <div class="product details product-item-details" style="min-height: 102px;">
        <strong class="product name product-item-name">
            <a class="product-item-link" href="<?php echo $productUrl;?>">
                <?php echo $productName;?>  </a>
            </strong>                              
            <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?php echo $productId;?>">
        <span class="price-container price-final_price tax weee">
                <span id="product-price-<?php echo $productId;?>" data-price-amount="<?php echo $productprice;?>" data-price-type="finalPrice" class="price-wrapper ">
                ₹<?php echo round($productprice);?>    </span>
                </span>
        </div>                        
            </div>
            </div>
            </li>
  <?php }
            }
            if (!empty($this->getRequest()->getPost('category')) && !empty($this->getRequest()->getPost('style'))){
                $style = $this->getRequest()->getPost('style');
                $category = $this->getRequest()->getPost('category');
                /*$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                $collection = $productCollection->addAttributeToSelect('*')
                ->addFieldToFilter('sku', array('in' => $productIdCat))
                ->addAttributeToFilter('style', $style)
                ->load();
                $productIdStyle = array();
                foreach ($collection as $product){ 
                $productIdStyle[] = $product->getSku();
                }
                echo json_encode($productIdStyle); //$productIdStyle
                }*/
                $skuTryons = "SELECT sku FROM camweara_table where category=$category and style=$style";
                $skuTryonresultss = $connection->fetchAll($skuTryons);
                $skuss = array();
                foreach($skuTryonresultss as $skuTryonresults){
                 $skuss[] = $skuTryonresults['sku'];
                 }
                  $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                /** Apply filters here */
                $collection = $productCollection->addAttributeToSelect('*')
                            ->addFieldToFilter('sku', array('in' => $skuss));
                $collection->setOrder('created_at', 'desc');
              /*  $collection->setPageSize(15);
                $collection->setCurPage(1);*/
            foreach ($collection as $product){ 
                $productUrl =  $product->getProductUrl(); 
                $productName = $product->getName();
                $productImage = $product->getSmallImage();
                $productprice = $product->getPrice();
                $productId = $product->getId();
                $product       = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
                $store         = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
                $imageUrl      = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();
                $mediaUrl      =      $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            ?>
            <li class="item product product-item" id="<?php echo $product->getSku();     ?>">
            <div class="product-item-info" data-container="product-grid">
            <div class="product-shop-top">
            <a href="<?php echo $productUrl;?>" class="product photo product-item-photo" tabindex="-1">
            <span class="product-image-container em-alt-hover" style="width:280px;">
            <span class="product-image-wrapper" style="padding-bottom: 100%;">
            <img class="product-image-photo" src="<?php echo $imageUrl;?>" width="280" height="280" alt="<?php echo $productName;?>"></span>
            </span>
            <span class="product-image-container em-alt-org" style="width:280px;">
            <span class="product-image-wrapper" style="padding-bottom: 100%;">
            <img class="product-image-photo" src="<?php echo $imageUrl;?>" width="280" height="280" alt="<?php echo $productName;?>"></span>
            </span>
            </a>
            <div class="em-element-display-hover bottom">
            </div>
            </div>
        <div class="product details product-item-details" style="min-height: 102px;">
        <strong class="product name product-item-name">
            <a class="product-item-link" href="<?php echo $productUrl;?>">
                <?php echo $productName;?>  </a>
            </strong>                              
            <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?php echo $productId;?>">
        <span class="price-container price-final_price tax weee">
                <span id="product-price-<?php echo $productId;?>" data-price-amount="<?php echo $productprice;?>" data-price-type="finalPrice" class="price-wrapper ">
                <span class="price">₹<?php echo round($productprice);?></span>    </span>
                </span>
        </div>                        
            </div>
            </div>
            </li>
  <?php }
            }
          /*  if (!empty($this->getRequest()->getPost('category')) && !empty($this->getRequest()->getPost('style')) && !empty($this->getRequest()->getPost('weight'))){
                $weight = $this->getRequest()->getPost('weight');
                $style = $this->getRequest()->getPost('style');
                $category = $this->getRequest()->getPost('category');
               /* $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                $collection = $productCollection->addAttributeToSelect('*')
                ->addFieldToFilter('sku', array('in' => $productIdStyle))
                ->addAttributeToFilter('weight', $weight)
                ->load();
                $productIdWeight = array();
                foreach ($collection as $product){ 
                $productIdWeight[] = $product->getSku();
                }
                echo json_encode($productIdWeight);  //$productIdWeight
                $skuTryons = "SELECT sku FROM camweara_table where category=$category and style=$style and weight=$weight";
                $skuTryonresultss = $connection->fetchAll($skuTryons);
                $skuss = array();
                foreach($skuTryonresultss as $skuTryonresults){
                 $skuss[] = $skuTryonresults['sku'];
                 }
                echo json_encode($skuss);
                }*/
             /*if (!empty($this->getRequest()->getPost('category')) && empty($this->getRequest()->getPost('style')) && !empty($this->getRequest()->getPost('weight'))){
                $weight = $this->getRequest()->getPost('weight');
                $style = $this->getRequest()->getPost('style');
                $category = $this->getRequest()->getPost('category');
               /* $productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
                $collection = $productCollection->addAttributeToSelect('*')
                ->addFieldToFilter('sku', array('in' => $productIdStyle))
                ->addAttributeToFilter('weight', $weight)
                ->load();
                $productIdWeight = array();
                foreach ($collection as $product){ 
                $productIdWeight[] = $product->getSku();
                }
                echo json_encode($productIdWeight); //$productIdWeight
                $skuTryons = "SELECT sku FROM camweara_table where category=$category and weight=$weight";
                $skuTryonresultss = $connection->fetchAll($skuTryons);
                $skuss = array();
                foreach($skuTryonresultss as $skuTryonresults){
                 $skuss[] = $skuTryonresults['sku'];
                 }
                echo json_encode($skuss);
                }*/
    }
    else{
        echo $is_unique = "false";
        $style = 'style';
        }
    }
}