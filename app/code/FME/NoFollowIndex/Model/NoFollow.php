<?php

/**
 * Class for NoFollowIndex NoFollow
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model;

class NoFollow extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'fme_nofollowindex';
	protected $_cacheTag = 'fme_nofollowindex';
	protected $_eventPrefix = 'fme_nofollowindex';

	protected function _construct()
	{
		$this->_init('FME\NoFollowIndex\Model\ResourceModel\NoFollow');
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
