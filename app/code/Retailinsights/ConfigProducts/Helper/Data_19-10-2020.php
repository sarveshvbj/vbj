<?php
namespace Retailinsights\ConfigProducts\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    protected $_resource;
    protected $_product;
    protected $_productTypeInstance;
    protected $_imageHelper;
    protected $_priceCurrencyObject;
    protected $_storeManager;
    protected $_quoteRepository;
    private $_quoteItemFactory;
    private $_itemResourceModel;

    const PURITY_TITLE = 'purity';
    const RINGS_TITLE = 'ring_sizes';
    const DIAMOND_TITLE = 'diamond_quality';
    const PURITY_ATTR = 'purity';
    const RINGS_ATTR = 'ringsize';
    const DIAMOND_ATTR = 'diamond_quality';

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface
     */
    public function __construct(\Magento\Framework\App\Helper\Context $context, 
      \Magento\Framework\ObjectManagerInterface $objectManager, 
      \Magento\Framework\App\ResourceConnection $resource,
      \Magento\Catalog\Model\Product $product,
      \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeInstance,
      \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrencyObject,
      \Magento\Store\Model\StoreManagerInterface $storeManager,
      \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
      \Magento\Quote\Model\ResourceModel\Quote\Item $itemResourceModel,
      \Magento\Quote\Model\QuoteRepository $quoteRepository,
      \Magento\Catalog\Helper\Image $imageHelper
    )
    {

        $this->_objectManager = $objectManager;
        $this->_resource = $resource;
        $this->_product = $product;
        $this->_productTypeInstance = $productTypeInstance;
        $this->_imageHelper = $imageHelper;
        $this->_priceCurrencyObject = $priceCurrencyObject;
        $this->_storeManager = $storeManager;
        $this->_quoteRepository = $quoteRepository;
        $this->_quoteItemFactory = $quoteItemFactory;
        $this->_itemResourceModel = $itemResourceModel;
        parent::__construct($context);
    }

    public function getCustomPrice($id, $sku, $ringsize, $purity, $diamond_quality)
    {
        $result = array();
        if($diamond_quality !="0") { 
      $diamond_quality_values = explode("-", $diamond_quality, 2);
      $diamond_color = $diamond_quality_values[0];
      $diamond_qual = $diamond_quality_values[1];
    } else {
      $diamond_color = "0";
      $diamond_qual = "0";
    }
    
        $connection = $this
            ->_resource
            ->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $customProductTable = $connection->getTableName('custom_config_products');
        $sql = "SELECT * FROM " . $customProductTable . " WHERE sku = ? AND size = ? AND purity = ? AND diamond_color = ? AND diamond_quality = ?";
        $product_values = $connection->fetchAll($sql, [$sku, $ringsize, $purity, $diamond_color, $diamond_qual]);

        $count = count($product_values);
        if ($count)
        {
            $product_values = reset($product_values);
            $price_values = $this->getCustomPriceOnly($product_values['id']);
            $result['status'] = 'success';
            $result['response'] = array_merge($product_values, $price_values);
        }
        else
        {
            $result['status'] = 'error';
            $result['message'] = 'The product does not exist or was not provided';
        }
        return $result;
    }

    public function getCustomPriceOnly($id)
    {
        $connection = $this
            ->_resource
            ->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $customProductPriceTable = $connection->getTableName('custom_config_products_price');
        $sql = "SELECT * FROM " . $customProductPriceTable . " WHERE id = ?";
        $product_values = $connection->fetchAll($sql, [$id]);
        return reset($product_values);
    }

        public function convertPrice($amount = 0, $store = null, $currency = null)
{
  
    if ($store == null) {
        $store = $this->_storeManager->getStore()->getStoreId(); 
        //get current store id if store id not get passed
    }
   $rate = $this->_priceCurrencyObject->convert($amount, $store, $currency);    
    return $rate;

}

    public function updateQuoteData($quoteId, $customData)
    {
        $quote = $this->_quoteRepository->get($quoteId); // Get quote by id
          $quote->setData('custom_price_flag', $customData);// Fill data
        $this->_quoteRepository->save($quote);
        $result = array();
        $result['code'] = $quote->getQuoteCurrencyCode();
        $result['rate'] = $quote->getBaseToQuoteRate();// Save quote
        return $result;
    }
     public function updateQuoteTotal($currencyCode, $quoteId)
    {
        $quote = $this->_quoteRepository->get($quoteId); // Get quote by id
         $inr_to_usd = $this->_storeManager->getStore()->getBaseCurrency()->getRate('USD');

        if($currencyCode == 'USD') {
           
            $grand_total = ($quote->getGrandTotal() * $inr_to_usd);
            $sub_total= ($quote->getSubTotal() * $inr_to_usd);
            $sub_total_with_discount= ($quote->getSubTotalWithDiscount() * $inr_to_usd);
            $base_sub_total_with_discount = $quote->getSubTotalWithDiscount();
            $base_sub_total = $quote->getSubTotal();
            $base_grand_total = $quote->getGrandTotal();

            //setting values
             $quote->setData('grand_total', number_format($grand_total,4));
             $quote->setData('base_grand_total', number_format($base_grand_total,4));
             $quote->setData('sub_total', number_format($sub_total,4));
             $quote->setData('base_sub_total', number_format($base_sub_total,4));
             $quote->setData('base_sub_total_with_discount', number_format($base_sub_total_with_discount,4));
             $quote->setData('sub_total_with_discount', number_format($sub_total_with_discount,4));

        } else if($currencyCode == 'INR') {
            
            $grand_total = ($quote->getGrandTotal() / $inr_to_usd);
            $sub_total= ($quote->getSubTotal() / $inr_to_usd);
            $sub_total_with_discount= ($quote->getSubTotalWithDiscount() / $inr_to_usd);
            $base_sub_total_with_discount = $sub_total_with_discount;
            $base_sub_total = $sub_total;
            $base_grand_total = $grand_total;
            //setting values
             $quote->setData('grand_total', number_format($grand_total,4));
             $quote->setData('base_grand_total', number_format($base_grand_total,4));
             $quote->setData('sub_total', number_format($sub_total,4));
             $quote->setData('base_sub_total', number_format($base_sub_total,4));
             $quote->setData('base_sub_total_with_discount', number_format($base_sub_total_with_discount,4));
             $quote->setData('sub_total_with_discount', number_format($sub_total_with_discount,4));
             // $quote->setGrandTotal(number_format($grand_total,4));
             // $quote->setBaseGrandTotal(number_format($base_grand_total,4));
             // $quote->setSubTotal(number_format($sub_total,4));
             // $quote->setBaseSubTotal(number_format($base_sub_total,4));
             // $quote->setSubTotalWithDiscount(number_format($sub_total_with_discount,4));
             // $quote->setBaseSubTotalWithDiscount(number_format($base_sub_total_with_discount,4));
        }
        $this->_quoteRepository->save($quote);
        $result = 'success';
        return $result;
    }
     public function updateQuoteItemData($quoteItemId, $customData)
    {
      $quoteItem = $this->_quoteItemFactory->create();
      $item = $this->_itemResourceModel->load($quoteItem, $quoteItemId);
          $item->setData('custom_price_flag', $customData); // Fill data
        $item->save(); // Save quote
    }

    public function getStrReplaceWithHyphen($value) {
return str_replace(' ','_', strtolower($value));
    }

    public function getProductColorImages($product_id) {

   $productcoll = $this->_product->load($product_id);
   $result = array();
  $color_value = $productcoll->getResource()->getAttribute('color')->getFrontend()->getValue($productcoll);

$imageUrl = $this->_imageHelper->init($productcoll, 'product_page_image_small')
                ->setImageFile($productcoll->getSmallImage()) // image,small_image,thumbnail
                ->resize(240)
                ->getUrl();
            $result = array($color_value,$imageUrl);
            
        return $result;        
  // $productcoll->getData('no_of_days');       

   $productAttributeOptions = $this->_productTypeInstance->getConfigurableAttributesAsArray($productcoll);

foreach ($productAttributeOptions as $key => $value) {
    $tmp_option = $value['values'];
    if(count($tmp_option) > 0)
    {
        foreach ($tmp_option as $tmp) 
        {
            echo "<option value='".$key."_".$tmp['value_index']."'>".$tmp['label']."</option>";
        }
    }
}

    }
           public function isColorAttr($product_id) {
   $productcoll = $this->_product->load($product_id);
  $result=false;      
  $productAttributeOptions = $this->_productTypeInstance->getConfigurableAttributesAsArray($productcoll);
foreach ($productAttributeOptions as $key => $value) {
    $tmp_option = $value['values'];
     if($value['attribute_code'] == "color") {
         $result = true;
     }
}
return $result;
    }
}

