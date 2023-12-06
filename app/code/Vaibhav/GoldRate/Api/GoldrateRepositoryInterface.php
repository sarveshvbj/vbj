<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface GoldrateRepositoryInterface
{

    /**
     * Save Goldrate
     * @param \Vaibhav\GoldRate\Api\Data\GoldrateInterface $goldrate
     * @return \Vaibhav\GoldRate\Api\Data\GoldrateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Vaibhav\GoldRate\Api\Data\GoldrateInterface $goldrate
    );

    /**
     * Retrieve Goldrate
     * @param string $goldrateId
     * @return \Vaibhav\GoldRate\Api\Data\GoldrateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($goldrateId);

    /**
     * Retrieve Goldrate matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vaibhav\GoldRate\Api\Data\GoldrateSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Goldrate
     * @param \Vaibhav\GoldRate\Api\Data\GoldrateInterface $goldrate
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Vaibhav\GoldRate\Api\Data\GoldrateInterface $goldrate
    );

    /**
     * Delete Goldrate by ID
     * @param string $goldrateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($goldrateId);
}

