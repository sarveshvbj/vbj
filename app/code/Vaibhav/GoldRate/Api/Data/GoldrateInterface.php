<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vaibhav\GoldRate\Api\Data;

interface GoldrateInterface
{

    const UPDATED_AT = 'updated_at';
    const TWENTYTWO_RATE = 'twentytwo_rate';
    const GOLDRATE_ID = 'goldrate_id';
    const TWENTYFOUR_RATE = 'twentyfour_rate';
    const CITY = 'city';

    /**
     * Get goldrate_id
     * @return string|null
     */
    public function getGoldrateId();

    /**
     * Set goldrate_id
     * @param string $goldrateId
     * @return \Vaibhav\GoldRate\Goldrate\Api\Data\GoldrateInterface
     */
    public function setGoldrateId($goldrateId);

    /**
     * Get city
     * @return string|null
     */
    public function getCity();

    /**
     * Set city
     * @param string $city
     * @return \Vaibhav\GoldRate\Goldrate\Api\Data\GoldrateInterface
     */
    public function setCity($city);

    /**
     * Get twentytwo_rate
     * @return string|null
     */
    public function getTwentytwoRate();

    /**
     * Set twentytwo_rate
     * @param string $twentytwoRate
     * @return \Vaibhav\GoldRate\Goldrate\Api\Data\GoldrateInterface
     */
    public function setTwentytwoRate($twentytwoRate);

    /**
     * Get twentyfour_rate
     * @return string|null
     */
    public function getTwentyfourRate();

    /**
     * Set twentyfour_rate
     * @param string $twentyfourRate
     * @return \Vaibhav\GoldRate\Goldrate\Api\Data\GoldrateInterface
     */
    public function setTwentyfourRate($twentyfourRate);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Vaibhav\GoldRate\Goldrate\Api\Data\GoldrateInterface
     */
    public function setUpdatedAt($updatedAt);
}

