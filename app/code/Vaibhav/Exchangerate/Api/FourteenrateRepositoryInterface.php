<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FourteenrateRepositoryInterface
{

    /**
     * Save fourteenrate
     * @param \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface $fourteenrate
     * @return \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface $fourteenrate
    );

    /**
     * Retrieve fourteenrate
     * @param string $fourteenrateId
     * @return \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($fourteenrateId);

    /**
     * Retrieve fourteenrate matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vaibhav\Exchangerate\Api\Data\FourteenrateSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete fourteenrate
     * @param \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface $fourteenrate
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Vaibhav\Exchangerate\Api\Data\FourteenrateInterface $fourteenrate
    );

    /**
     * Delete fourteenrate by ID
     * @param string $fourteenrateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($fourteenrateId);
}

