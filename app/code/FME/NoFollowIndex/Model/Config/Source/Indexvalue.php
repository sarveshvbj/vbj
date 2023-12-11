<?php

/**
 * Class for NoFollowIndex Indexvalue
 * @Copyright © FME fmeextensions.com. All rights reserved.
 * @autor Arsalan Ali Sadiq <support@fmeextensions.com>
 * @package FME NoFollowIndex
 * @license See COPYING.txt for license details.
 */

namespace FME\NoFollowIndex\Model\Config\Source;

class Indexvalue extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function toOptionArray()
    {
      return [['value' => '1', 'label' => __('Index')],
              ['value' => '2', 'label' => __('No Index')]
          ];
    }
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
