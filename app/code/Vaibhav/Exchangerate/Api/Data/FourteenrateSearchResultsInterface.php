<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Api\Data;

interface FourteenrateSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get fourteenrate list.
     * @return \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface[]
     */
    public function getItems();

    /**
     * Set fourteen_rate list.
     * @param \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

