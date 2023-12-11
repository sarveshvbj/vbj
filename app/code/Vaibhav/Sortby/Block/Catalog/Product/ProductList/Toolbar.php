<?php

namespace Vaibhav\Sortby\Block\Catalog\Product\ProductList;

use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    /**
     * Set collection to pager
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
       if ($this->getCurrentOrder()) {
            switch ($this->getCurrentOrder()) {
                case 'price':
                    /*$this->_collection->setOrder($this->getCurrentOrder(), 'ASC');*/
                    $this->_collection->getSelect()->order('price DESC');
                    break;

                case 'price_desc':
                    $this->_collection->getSelect()->order('price DESC');
                    break;
                            
                case 'news_to_date':
                $this->_collection->getSelect()->order('e.created_at ASC');
                break;
                
                case 'oldest':
                $this->_collection->getSelect()->order('e.created_at DESC');
                break;

                default:
                    //$this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
                    $this->_collection->setOrder($this->getCurrentOrder(), 'DESC');
                break;
            }
        }
   /*     echo '<pre>';
    //var_dump($this->getCurrentOrder());
    echo $this->_collection->getSelect();
    die;*/
        return $this;
    }

}