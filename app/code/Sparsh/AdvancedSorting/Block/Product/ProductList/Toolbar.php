<?php
/**
 * Class Toolbar
 *
 * PHP version 7
 *
 * @category Sparsh
 * @package  Sparsh_AdvancedSorting
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
namespace Sparsh\AdvancedSorting\Block\Product\ProductList;

/**
 * Class Toolbar
 *
 * @category Sparsh
 * @package  Sparsh_AdvancedSorting
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    /**
     * Set collection to sorting option
     *
     * @param \Magento\Framework\Data\Collection $collection
     *
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
        $this->_collection->setCurPage($this->getCurrentPage());

        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }

        if ($this->getCurrentOrder()) {

            /*if($this->getCurrentOrder() == 'created_at'){
                $this->_collection->setOrder('created_at', 'desc');
            }*/
            switch ($this->getCurrentOrder()) {
                case 'price_low_high':
                    $this->_collection->setOrder('price', 'asc');
                    break;
                case 'price_high_low':
                    $this->_collection->setOrder('price', 'desc');
                    break;
                case 'latest':
                    $this->_collection->setOrder('created_at', 'desc');
                    break;
                case 'oldest':
                    $this->_collection->setOrder('created_at', 'asc');
                    break;
                case 'new':
                    $this->_collection->setOrder('created_at', 'desc');
                    break;
                default:
                    $this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirectionReverse());
                    break;
            }

        }

        return $this;
    }

    /**
     * Return Reverse direction of current direction
     *
     * @return string
     */
    public function getCurrentDirectionReverse()
    {
        if ($this->getCurrentDirection() == 'asc') {
            return 'desc';
        } elseif ($this->getCurrentDirection() == 'desc') {
            return 'asc';
        } else {
            return $this->getCurrentDirection();
        }
    }
}
