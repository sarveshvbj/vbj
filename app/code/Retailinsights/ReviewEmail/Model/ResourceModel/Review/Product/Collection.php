<?php

namespace Retailinsights\ReviewEmail\Model\ResourceModel\Review\Product;

class Collection extends \Magento\Review\Model\ResourceModel\Review\Product\Collection
{
   
  protected function _joinFields()
{
    $this->getSelect()->columns('rdt.email');

    return parent::_joinFields();
}


}
