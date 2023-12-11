<?php
namespace Retailinsights\ConfigProducts\Model;

use Retailinsights\ConfigProducts\Api\ConfigProductInterface;

class CustomConfigProducts implements ConfigProductInterface
{
	protected $_Config_products;
	protected $_Config_products_factory;
	protected $_Config_products_price;
	protected $_Config_products_priceFactory;
	protected $_product;
	protected $_jsonHelper;
	protected $_helperData;

	public function __construct(
		\Retailinsights\ConfigProducts\Model\ConfigProductsFactory $ConfigProductsFactory,
		\Retailinsights\ConfigProducts\Model\ConfigProducts $ConfigProducts,
		\Retailinsights\ConfigProducts\Model\ConfigProductsPriceFactory $ConfigProductsPriceFactory,
		\Retailinsights\ConfigProducts\Model\ConfigProductsPrice $ConfigProductsPrice,
		\Magento\Catalog\Model\Product $product,
		\Magento\Framework\Json\Helper\Data $jsonHelper,
		\Retailinsights\ConfigProducts\Helper\Data $helperData
	)
	{
		$this->_Config_products = $ConfigProducts;
		$this->_Config_products_factory = $ConfigProductsFactory;
		$this->_Config_products_price = $ConfigProductsPrice;
		$this->_Config_products_priceFactory = $ConfigProductsPriceFactory;
		$this->_product = $product;
		$this->_jsonHelper = $jsonHelper;
		$this->_helperData = $helperData;
	}

	/**
	 * POST getcustomprice
	 * @param string $id
	 * @param string $sku
	 * @param string $ringsize
	 * @param string $purity
	 * @param string $diamond_quality
	 * @return string
	 */
	public function getcustomprice($id, $sku, $ringsize, $purity, $diamond_quality) {
		if($diamond_quality !="0") { 
			$diamond_quality_values = explode("-", $diamond_quality, 2);
			$diamond_color = $diamond_quality_values[0];
			$diamond_qlty = $diamond_quality_values[1];
		} else {
			$diamond_color = "0";
			$diamond_qlty = "0";
		}
		
		$wherecolumns = array('sku', 'size', 'purity', 'diamond_color', 'diamond_quality');
		$wherevalues = array($sku, $ringsize, $purity, $diamond_color, $diamond_qlty);

		$result = array();
		try {
       // $rmaorderModel = $this->_Config_products->create();

     // For get result from direct connection
		$result = $this->_helperData->getCustomPrice($id, $sku, $ringsize, $purity, $diamond_quality);

			// $value = $this->_Config_products_factory->create()->getCollection()
			// 	->addFieldToFilter('sku', $sku)
			// 	->addFieldToFilter('size', $ringsize)
			// 	->addFieldToFilter('purity', $purity)
			// 	->addFieldToFilter('diamond_color', $diamond_color)
			// 	->addFieldToFilter('diamond_quality', $diamond_qlty);
			// 	$count = count($value->getData());
			// if ($this->_product->getIdBySku($sku) && $count) {
			// 	$pricecollection = $this->_Config_products_priceFactory->create()->getCollection()->addFieldToFilter('id', $value->getData('id'));
			// 	$products = $value->getData();
			// 	$products_col = array_shift($products);
			// 	$price_values = $pricecollection->getData();
			// 	$product_prices = array_shift($price_values);
			// 	$result['status']="success"; 
			// 	$result['response']= array_merge($products_col, $product_prices);

			// } else {
			// 	$result['status']="error"; 
			// 	$result['message'] ="The product does not exist or was not provided";
				
			// }


		} catch (\Exception $e) {
			$result = array(["status"=>"error",'message' => $e->getMessage()]);
		}

		return [$result];
	}
}
