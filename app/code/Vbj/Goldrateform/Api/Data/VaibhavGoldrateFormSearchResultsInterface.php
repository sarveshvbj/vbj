<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Api\Data;

interface VaibhavGoldrateFormSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get vaibhav_goldrate_form list.
     * @return \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface[]
     */
    public function getItems();

    /**
     * Set customer_name list.
     * @param \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

