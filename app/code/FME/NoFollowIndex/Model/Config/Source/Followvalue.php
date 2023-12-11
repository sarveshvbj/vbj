<?php

/**
 * Class for NoFollowIndex Followvalue
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model\Config\Source;

class Followvalue extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function toOptionArray()
    {
      return [['value' => '1', 'label' => __('Follow')],
              ['value' => '2', 'label' => __('No Follow')]
          ];
    }
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
