<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Model\ResourceModel\VaibhavGoldrateForm;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'vaibhav_goldrate_form_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Vbj\Goldrateform\Model\VaibhavGoldrateForm::class,
            \Vbj\Goldrateform\Model\ResourceModel\VaibhavGoldrateForm::class
        );
    }
}

