<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package RMA Base for Magento 2
 */

namespace Amasty\Rma\Model\ReturnRules\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ReturnRulesCustomerGroupsCollection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\Rma\Model\ReturnRules\ReturnRulesCustomerGroups::class,
            \Amasty\Rma\Model\ReturnRules\ResourceModel\ReturnRulesCustomerGroups::class
        );
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
