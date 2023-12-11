<?php

/**
 * Class for NoFollowIndex CategoryPluginAddData
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Plugin;

class CategoryPluginAddData
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
		// echo '<pre>';
		// print_r('enable: ' . $result->getData('nofollowindex_enable'));
		// print_r('follow: ' . $result->getData('nofollowindex_followvalue'));
		// print_r('index: ' . $result->getData('nofollowindex_indexvalue'));
		// print_r('archive: ' . $result->getData('nofollowindex_noarchivevalue'));
		// print_r('enable on prod: ' . $result->getData('nofollowindex_enableonproducts'));
		// print_r('priority: ' . $result->getData('nofollowindex_priority'));
		// exit;
		if ($this->_helper->enableNoFollowIndexExtension() == 1)
		{
					$categoryId = (string)$result->getId();
					$followvalue = (string)$result->getData('nofollowindex_followvalue');
					$indexvalue = (string)$result->getData('nofollowindex_indexvalue');
					$enablevalue = (string)$result->getData('nofollowindex_enable');
					$enableonproducts = (string)$result->getData('nofollowindex_enableonproducts');
					$noarchivevalue = (string)$result->getData('nofollowindex_noarchivevalue');
					$priority = (string)$result->getData('nofollowindex_priority');
					$type = 'category';
					$table = $this->_coreResource->getTableName('fme_nofollowindex');
					$selectdatasql = 'select * from ' . $table . ' where (nofollowindex_itemid =' . $categoryId . ') and (nofollowindex_itemtype=\'category\')';
					$connection = $this->_coreResource->getConnection('core_read');
					$resultquery = $connection->fetchAll($selectdatasql);
					if (sizeof($resultquery) > 0)
					{
						$updatedatasql = "update " . $table . " SET nofollowindex_itemfollowvalue = '$followvalue', nofollowindex_itemindexvalue = '$indexvalue', nofollowindex_itemenablevalue = '$enablevalue', nofollowindex_enableonproducts= '$enableonproducts', nofollowindex_priority= '$priority', nofollowindex_itemnoarchivevalue= '$noarchivevalue' where nofollowindex_itemid = '$categoryId' and nofollowindex_itemtype = 'category'";
						$connection = $this->_coreResource->getConnection('core_write');
						$resultquery = $connection->query($updatedatasql);
					}
					else
					{
						$insertdatasql = "insert into " . $table . " (nofollowindex_itemid, nofollowindex_itemtype, nofollowindex_itemfollowvalue, nofollowindex_itemindexvalue, nofollowindex_itemenablevalue, nofollowindex_enableonproducts, nofollowindex_priority,nofollowindex_itemnoarchivevalue) VALUES ('". $categoryId . "','" . $type . "','". $followvalue . "','". $indexvalue . "','". $enablevalue . "','". $enableonproducts . "','". $priority . "','". $noarchivevalue . "')";
						$connection = $this->_coreResource->getConnection('core_write');
						$resultquery = $connection->query($insertdatasql);
					}
		}
		return $result;

  }
}
