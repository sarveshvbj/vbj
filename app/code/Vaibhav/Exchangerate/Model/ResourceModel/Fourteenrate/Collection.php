<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Model\ResourceModel\Fourteenrate;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'fourteenrate_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Vaibhav\Exchangerate\Model\Fourteenrate::class,
            \Vaibhav\Exchangerate\Model\ResourceModel\Fourteenrate::class
        );
    }
}

