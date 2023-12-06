<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Api\Data;

interface GoldrateSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Goldrate list.
     * @return \Vaibhav\GoldRate\Api\Data\GoldrateInterface[]
     */
    public function getItems();

    /**
     * Set city list.
     * @param \Vaibhav\GoldRate\Api\Data\GoldrateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

