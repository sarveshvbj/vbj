<?php

/**
 * Class for NoFollowIndex NoFollow
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model\ResourceModel;


class NoFollow extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('fme_nofollowindex', 'Id');
	}

}
