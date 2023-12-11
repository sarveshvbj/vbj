<?php

/**
 * Class for NoFollowIndex ProductPluginAddData
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Plugin;

class ProductPluginAddData
{
	protected $_helper;
	protected $_coreResource;

	public function __construct(
		\FME\NoFollowIndex\Helper\Data $helper,
		\Magento\Framework\App\ResourceConnection $coreResource
	)
	{
		$this->_helper = $helper;
		$this->_coreResource = $coreResource;
	}

  public function afterAfterSave($result)
  {
		if ($this->_helper->enableNoFollowIndexExtension() == 1)
		{
			$productId = (string)$result->getId();
			$followvalue = (string)$result->getData('nofollowindex_followvalue');
			$indexvalue = (string)$result->getData('nofollowindex_indexvalue');
			$noarchivevalue = (string)$result->getData('nofollowindex_noarchivevalue');
			$enablevalue = (string)$result->getData('nofollowindex_enable');
			$type = 'product';
			$table = $this->_coreResource->getTableName('fme_nofollowindex');
			$selectdatasql = 'select * from ' . $table . ' where (nofollowindex_itemid =' . $productId . ') and (nofollowindex_itemtype=\'product\')';
			$connection = $this->_coreResource->getConnection('core_read');
			$resultquery = $connection->fetchAll($selectdatasql);
			if (sizeof($resultquery) > 0)
			{
				$updatedatasql = "update " . $table . " SET nofollowindex_itemfollowvalue = '$followvalue', nofollowindex_itemindexvalue = '$indexvalue', nofollowindex_itemenablevalue = '$enablevalue', nofollowindex_itemnoarchivevalue = '$noarchivevalue' where nofollowindex_itemid = '$productId' and nofollowindex_itemtype = 'product'";
				$connection = $this->_coreResource->getConnection('core_write');
				$resultquery = $connection->query($updatedatasql);
			
			}
			else
			{
				$insertdatasql = "insert into " . $table . " (nofollowindex_itemid, nofollowindex_itemtype, nofollowindex_itemfollowvalue, nofollowindex_itemindexvalue, nofollowindex_itemenablevalue, nofollowindex_itemnoarchivevalue) VALUES ('". $productId . "','" . $type . "','". $followvalue . "','". $indexvalue . "','". $enablevalue . "','" . $noarchivevalue ."')";
				$connection = $this->_coreResource->getConnection('core_write');
				$resultquery = $connection->query($insertdatasql);
			}
		}
		return $result;

  }
}
