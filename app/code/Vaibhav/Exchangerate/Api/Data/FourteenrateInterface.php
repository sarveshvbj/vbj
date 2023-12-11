<?php
declare(strict_types=1);

namespace Vaibhav\Exchangerate\Api\Data;

interface FourteenrateInterface
{

    const TWENTYTWO_RATE = 'twentytwo_rate';
    const TWENTYFOUR_RATE = 'twentyfour_rate';
    const FOURTEENRATE_ID = 'fourteenrate_id';
    const EIGHTEEN_RATE = 'eighteen_rate';
    const FOURTEEN_RATE = 'fourteen_rate';

    /**
     * Get fourteenrate_id
     * @return string|null
     */
    public function getFourteenrateId();

    /**
     * Set fourteenrate_id
     * @param string $fourteenrateId
     * @return \Vaibhav\Exchangerate\Fourteenrate\Api\Data\FourteenrateInterface
     */
    public function setFourteenrateId($fourteenrateId);

    /**
     * Get fourteen_rate
     * @return string|null
     */
    public function getFourteenRate();

    /**
     * Set fourteen_rate
     * @param string $fourteenRate
     * @return \Vaibhav\Exchangerate\Fourteenrate\Api\Data\FourteenrateInterface
     */
    public function setFourteenRate($fourteenRate);

    /**
     * Get eighteen_rate
     * @return string|null
     */
    public function getEighteenRate();

    /**
     * Set eighteen_rate
     * @param string $eighteenRate
     * @return \Vaibhav\Exchangerate\Fourteenrate\Api\Data\FourteenrateInterface
     */
    public function setEighteenRate($eighteenRate);

    /**
     * Get twentytwo_rate
     * @return string|null
     */
    public function getTwentytwoRate();

    /**
     * Set twentytwo_rate
     * @param string $twentytwoRate
     * @return \Vaibhav\Exchangerate\Fourteenrate\Api\Data\FourteenrateInterface
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
     * @return \Vaibhav\Exchangerate\Fourteenrate\Api\Data\FourteenrateInterface
     */
    public function setTwentyfourRate($twentyfourRate);
}

