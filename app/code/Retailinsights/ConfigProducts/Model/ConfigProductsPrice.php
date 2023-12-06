<?php
namespace Retailinsights\ConfigProducts\Model;

class ConfigProductsPrice extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'retailinsights_configproducts_configproductsprice';

	protected $_cacheTag = 'retailinsights_configproducts_configproductsprice';

	protected $_eventPrefix = 'retailinsights_configproducts_configproductsprice';
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Retailinsights\ConfigProducts\Model\ResourceModel\ConfigProductsPrice');
    }

    public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
?>