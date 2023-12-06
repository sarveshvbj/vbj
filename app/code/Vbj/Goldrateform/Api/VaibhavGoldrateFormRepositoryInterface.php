<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vbj\Goldrateform\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface VaibhavGoldrateFormRepositoryInterface
{

    /**
     * Save vaibhav_goldrate_form
     * @param \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface $vaibhavGoldrateForm
     * @return \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface $vaibhavGoldrateForm
    );

    /**
     * Retrieve vaibhav_goldrate_form
     * @param string $vaibhavGoldrateFormId
     * @return \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($vaibhavGoldrateFormId);

    /**
     * Retrieve vaibhav_goldrate_form matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete vaibhav_goldrate_form
     * @param \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface $vaibhavGoldrateForm
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Vbj\Goldrateform\Api\Data\VaibhavGoldrateFormInterface $vaibhavGoldrateForm
    );

    /**
     * Delete vaibhav_goldrate_form by ID
     * @param string $vaibhavGoldrateFormId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($vaibhavGoldrateFormId);
}

