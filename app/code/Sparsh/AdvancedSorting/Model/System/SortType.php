<?php
/**
 * Class SortType
 *
 * PHP version 7
 *
 * @category Sparsh
 * @package  Sparsh_AdvancedSorting
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
namespace Sparsh\AdvancedSorting\Model\System;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class SortType
 *
 * @category Sparsh
 * @package  Sparsh_AdvancedSorting
 * @author   Sparsh <magento@sparsh-technologies.com>
 * @license  https://www.sparsh-technologies.com  Open Software License (OSL 3.0)
 * @link     https://www.sparsh-technologies.com
 */
class SortType implements ArrayInterface
{
    /*const BEST_SELLER  = "best_seller";
    const TOP_RATED = "top_rated";
    const NEW_ARRIVALS = "created_at";
    const MOST_VIEWED = "most_viewed";
    const REVIEW_COUNT = "review_count";*/
    const PRICE_LOW_HIGH = "price_low_high";
    const PRICE_HIGH_LOW = "price_high_low";
    const LATEST = "latest";
    const OLDEST = "oldest";

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getOptionHash() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    /**
     * Return options
     *
     * @return array
     */
    public function getOptionHash()
    {
        return [
            /*self::BEST_SELLER  => __('Best Seller'),
            self::TOP_RATED => __('Top Rated'),
            self::NEW_ARRIVALS => __('New Arrivals'),
            self::MOST_VIEWED => __('Most Viewed'),
            self::REVIEW_COUNT => __('Review Count'),*/
            self::PRICE_LOW_HIGH => __('Price Low to High'),
            self::PRICE_HIGH_LOW => __('Price High to Low'),
            self::LATEST => __('Latest'),
            self::OLDEST => __('Oldest'),
        ];
    }
}
