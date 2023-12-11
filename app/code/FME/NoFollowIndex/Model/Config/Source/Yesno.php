<?php

/**
 * Class for NoFollowIndex Yesno
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model\Config\Source;

class Yesno extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function toOptionArray()
    {
      return [['value' => '0', 'label' => __('No')],
      ['value' => '1', 'label' => __('Yes')]
          ];
    }
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
