<?php

/**
 * Class for NoFollowIndex Fieldsgroup
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Block\Adminhtml\Form\Field;

class Fieldsgroup extends \Magento\Framework\View\Element\Html\Select
{
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function setInputName($value)
    {
        return $this->setName($value);
    }

    public function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = [];
            $options[1] = 'Follow';
            $options[2] = 'No Follow';

            foreach ($options as $groupId => $groupLabel) {
                $this->addOption($groupId, addslashes($groupLabel));
            }
        }
        return parent::_toHtml();
    }
}
