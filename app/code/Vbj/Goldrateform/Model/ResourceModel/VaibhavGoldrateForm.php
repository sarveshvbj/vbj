<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class VaibhavGoldrateForm extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('vbj_goldrateform_vaibhav_goldrate_form', 'vaibhav_goldrate_form_id');
    }
}

