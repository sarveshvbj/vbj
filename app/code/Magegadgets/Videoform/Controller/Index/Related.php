<?php

namespace Magegadgets\Videoform\Controller\Index;
use Magento\Framework\App\Action\Context;
ob_start();

class Related extends \Magento\Framework\App\Action\Action
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
           if (!empty($this->getRequest()->getPost('product_id'))){

              $limit=20;

              $product_id = $this->getRequest()->getPost('product_id');
              $category_id = $this->getRequest()->getPost('categoryid');
              $productcoll = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
              $categorylist = $productcoll->getCategoryIds();
              
              $ids = array_reverse($productcoll->getCategoryIds());
              $first_category = $ids[0];
              $category = $objectManager->get('Magento\Catalog\Model\Category')->load($ids[0]);

               $collection = $objectManager->get('Magento\Catalog\Model\ResourceModel\Product\Collection')
                ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
                ->addAttributeToFilter('status', 1)
                //->addAttributeToFilter('qty', array('gt' => 0))
                ->addCategoryFilter($category)
                ->addAttributeToSelect('*')
                ->setPageSize($limit);
                if ($productcoll) {
                    $collection->addAttributeToFilter('entity_id', array(
                        'neq' => $product_id));
                }

                 $collection->getSelect()->order(new \Zend_Db_Expr('RAND()')); ?>
                 <div class="tcb-product-slider">
                 <?php

                 $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
                 $store = $storeManager->getStore();

            foreach ($collection as $product){ 
                  
                 
                $productName = $product->getName();
                $productImage = $product->getSmallImage();
                $productprice = $product->getFinalPrice();
                $productId = $product->getId();
                $product       = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
                $helperImport = $objectManager->get('\Magento\Catalog\Helper\Image');
                $store         = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
               $imageUrl = $helperImport->init($product, 'product_page_image_swatch')
                ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                ->resize(200)
                ->getUrl();
                $mediaUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $ids = array_reverse($product->getCategoryIds());
              $first_category = $ids[0];
              // if($category_id){
              //               $_category = $objectManager->create('Magento\Catalog\Model\Category')->load($category_id);
              //               $categorykey = $_category->getUrl();
              //               $wod=parse_url($categorykey, PHP_URL_PATH);
              //               $url = explode('.', $wod);
              //               array_pop($url);
              //               $categorykey=substr(implode('.', $url), 1); 
              //               $siteurl= $store->getBaseUrl();      
              //               $productUrl=$siteurl.$categorykey."/".$product->getUrlKey().".html";
              //               }
              //               else{
                               $productUrl =  $product->getProductUrl();
                           // }
            ?>
                 <div>
    <div class="tcb-product-item <?php echo  $first_category.'_'.$category_id; ?>">
                                    <div class="tcb-product-photo">
                                        <a href="<?php echo $productUrl;?>"><img src="<?php echo $imageUrl;?>" class="img-responsive" alt="a" /></a>
                                    </div>
                                    <div class="tcb-product-info">
                                        <div class="tcb-product-title">
                                            <div class="product-name"><a href="<?php echo $productUrl;?>"><?php echo $productName;?></a></div></div>
                                        <div class="tcb-product-price">
                                            ₹<?php echo round($productprice);?>
                                        </div>
                                    </div>
                                </div>
  </div>
  <?php } ?>
</div>
  <?php
            }
    }
    else{
        echo $is_unique = "false";
        $style = 'style';
        }
    }
}