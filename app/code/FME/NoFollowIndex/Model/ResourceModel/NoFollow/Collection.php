<?php

/**
 * Class for NoFollowIndex Collection
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model\ResourceModel\NoFollow;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'Id';
	protected $_eventPrefix = 'fme_nofollowindex_collection';
	protected $_eventObject = 'fme_nofollowindex';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('FME\NoFollowIndex\Model\NoFollow', 'FME\NoFollowIndex\Model\ResourceModel\NoFollow');
	}

	public function addIdandTypeFilter($id, $type)
	{
		$this->getSelect()
						->where('main_table.nofollowindex_itemtype ="' . $type . '" and main_table.nofollowindex_itemid = ' . $id);
		return $this;
	}
}
