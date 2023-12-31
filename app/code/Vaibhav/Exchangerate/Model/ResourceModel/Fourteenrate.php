<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Fourteenrate extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('vaibhav_exchangerate_fourteenrate', 'fourteenrate_id');
    }
}

